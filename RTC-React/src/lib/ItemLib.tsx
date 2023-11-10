import { Cookies } from "react-cookie";
import { iAvailableItems } from "../components/ItemSelecter";
import Echo from 'laravel-echo';
import Pusher from 'pusher-js';

var pusher = new Pusher(
    import.meta.env.VITE_PUSHER_APP_KEY, 
    { cluster: import.meta.env.VITE_PUSHER_APP_CLUSTER }
);

const echo = new Echo({
    broadcaster: 'pusher',
    key: import.meta.env.VITE_PUSHER_APP_KEY,
    cluster: import.meta.env.VITE_PUSHER_APP_CLUSTER,
    forceTLS: true
});

async function GetItemsPolling() { }

async function GetItemsSSE() {
    const cookies = new Cookies();
    let userId = 0;

    if (cookies.get('user_id') == null) {
        userId = 1;
        cookies.set('user_id', userId, { path: '/' });
    } else {
        userId = cookies.get('user_id');
    }

    return new Promise((resolve) => {
        const channel = echo.channel('Parkingspot.' + userId);
        console.log(channel);

        const pusherChannel = pusher.subscribe('channel-name');
        console.log(pusherChannel);

        pusherChannel.bind('LolEvent', (data: iAvailableItems[]) => {
            console.log(data);
            resolve(data); // Resolve the promise with the received data
        });

        // channel.listen('AvailableParkingspots', (data: iAvailableItems[]) => {
        //     console.log(data);
        //     resolve(data); // Resolve the promise with the received data
        // });
    });
}

function GetItemsStreaming() {
    const cookies = new Cookies();
    if (cookies.get('user_id') == null)
    {
        cookies.set('user_id', 1, { path: '/' });
    }
    
    return new Promise((resolve, reject) => {
        let user_id = cookies.get('user_id');
        console.log(user_id);
        const eventSource = new EventSource('http://localhost:80/api/items/available/' + user_id);
        
        let oldData: iAvailableItems[] = [];

        eventSource.onmessage = (event) => {
            const new_user_id = cookies.get('user_id');
            if (new_user_id != user_id)
            {
                console.log(new_user_id);
                user_id = new_user_id;
            }
            // This function will be called when a new event is received.
            const data = JSON.parse(event.data) as iAvailableItems[]; // Parse the data if it's in JSON format
            if (JSON.stringify(data) == JSON.stringify(oldData))
            {
                console.log(data);
                oldData = data;
            }
            // You can process the data here
            resolve(data); // Resolve the Promise with the received data
        };

        eventSource.onerror = (error) => {
            console.error('Error:', error);
            // Handle the error or return an error message if needed
            eventSource.close(); // Close the EventSource in case of an error
            reject(error); // Reject the Promise in case of an error
        }
    });
}

async function GetItemsWS() { 

}

async function basPusher(){
    const pusher = new Pusher(import.meta.env.VITE_PUSHER_APP_KEY, {
        cluster: import.meta.env.VITE_PUSHER_APP_CLUSTER,
        forceTLS: true
    });

    const channel = pusher.subscribe('basChannel');
    channel.bind('basEvent', function(data) {
        alert(JSON.stringify(data));
    });
}

async function ReserveItem(userId:number, itemId:number) {
    const reserveItem = {
        user_id: userId,
        item_id: itemId
    };

    const requestOptions = {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify(reserveItem)
    };

    try {
        const response = await fetch('http://localhost:80/api/item/reserve', requestOptions);
        
        if (!response.ok) {
            throw new Error('Network response was not ok');
        }
        else{
            const data = await response.json();
            console.log(data);
        }
        
        return "success";
    } catch (error) {
        console.error('Error:', error);
        // Handle the error or return an error message if needed
        return null;
    }
}

export { GetItemsPolling, GetItemsStreaming, GetItemsSSE, GetItemsWS, ReserveItem };
