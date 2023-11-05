# POC-RTC-Laravel
 Proof of Concept for Real-time Communication Protocols with Laravel as back-end

## Real-time Communication Protocols
Real-time Communication Protocol, also known as RTC.
### API Polling
The easiest way of RTC. 
<br>
Every once in a while the client will sent a request to the server waiting on a response, in the hope that there will be some new data. 

### <a href="./Server Sent Events/">Server Sent Events</a>
A one way RTC connection. 
<br>
The client will start a connection by sending a request, as following the server will keep the connection open and send events when data is updated. In this protocol the client isn't able to send requests after the connection is opened.

### Web Sockets
A bidirectional RTC connection.
<br>
With websockets the client opens a connection over which the client can make requests and the server will send responses. The responses received from the server don't have to be in chronological order. 

### WebRTC
This is a protocol of which no demonstration is created, the reason behind this is because this protocol is mostly used for chatting, voice calls and video calls via web applications. Which for this project is not needed.