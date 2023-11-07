async function GetItemsPolling() { }

async function GetItemsSSE() { }

async function GetItemsWS() { }

async function ReserveItem(userId:number, itemId:number) {
    const reserveItem = {
        userId: userId,
        itemId: itemId
    };

    const requestOptions = {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify(reserveItem)
    };

    try {
        const response = await fetch('http://localhost:5000/api/ReserveItem', requestOptions);
        
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
