// telemedicina
// When the DOM is ready
document.addEventListener(
  "DOMContentLoaded",
  function(event) {
    // ARRASTRAR ELEMENTO
    dragElement(document.getElementById("camara-doctor"));

    function dragElement(elmnt) {
      var pos1 = 0,
        pos2 = 0,
        pos3 = 0,
        pos4 = 0;

      elmnt.onmousedown = dragMouseDown;

      function dragMouseDown(e) {
        e = e || window.event;
        e.preventDefault();
        // get the mouse cursor position at startup:
        pos3 = e.clientX;
        pos4 = e.clientY;
        document.onmouseup = closeDragElement;
        // call a function whenever the cursor moves:
        document.onmousemove = elementDrag;
      }

      function elementDrag(e) {
        e = e || window.event;
        e.preventDefault();
        // calculate the new cursor position:
        pos1 = pos3 - e.clientX;
        pos2 = pos4 - e.clientY;
        pos3 = e.clientX;
        pos4 = e.clientY;
        // set the element's new position:
        elmnt.style.top = elmnt.offsetTop - pos2 + "px";
        elmnt.style.left = elmnt.offsetLeft - pos1 + "px";
      }

      function closeDragElement() {
        // stop moving when mouse button is released:
        document.onmouseup = null;
        document.onmousemove = null;
      }
    }

    // AGRANDAR REDUCIR ELEMENTO VIDEO
    function makeResizableDiv(div) {
      const element = document.querySelector(div);
      const resizers = document.querySelectorAll(div + " .resizer");
      const minimum_size = 20;
      let original_width = 0;
      let original_height = 0;
      let original_x = 0;
      let original_y = 0;
      let original_mouse_x = 0;
      let original_mouse_y = 0;
      for (let i = 0; i < resizers.length; i++) {
        const currentResizer = resizers[i];
        currentResizer.addEventListener("mousedown", function(e) {
          e.preventDefault();
          original_width = parseFloat(
            getComputedStyle(element, null)
              .getPropertyValue("width")
              .replace("px", "")
          );
          original_height = parseFloat(
            getComputedStyle(element, null)
              .getPropertyValue("height")
              .replace("px", "")
          );
          original_x = element.getBoundingClientRect().left;
          original_y = element.getBoundingClientRect().top;
          original_mouse_x = e.pageX;
          original_mouse_y = e.pageY;
          window.addEventListener("mousemove", resize);
          window.addEventListener("mouseup", stopResize);
        });

        function resize(e) {
          if (currentResizer.classList.contains("bottom-right")) {
            const width = original_width + (e.pageX - original_mouse_x);
            const height = original_height + (e.pageY - original_mouse_y);
            if (width > minimum_size) {
              element.style.width = width + "px";
            }
            if (height > minimum_size) {
              element.style.height = height + "px";
            }
          } else if (currentResizer.classList.contains("bottom-left")) {
            const height = original_height + (e.pageY - original_mouse_y);
            const width = original_width - (e.pageX - original_mouse_x);
            if (height > minimum_size) {
              element.style.height = height + "px";
            }
            if (width > minimum_size) {
              element.style.width = width + "px";
              element.style.left =
                original_x + (e.pageX - original_mouse_x) + "px";
            }
          } else if (currentResizer.classList.contains("top-right")) {
            const width = original_width + (e.pageX - original_mouse_x);
            const height = original_height - (e.pageY - original_mouse_y);
            if (width > minimum_size) {
              element.style.width = width + "px";
            }
            if (height > minimum_size) {
              element.style.height = height + "px";
              element.style.top =
                original_y + (e.pageY - original_mouse_y) + "px";
            }
          } else {
            const width = original_width - (e.pageX - original_mouse_x);
            const height = original_height - (e.pageY - original_mouse_y);
            if (width > minimum_size) {
              element.style.width = width + "px";
             element.style.left =
               original_x + (e.pageX - original_mouse_x) + "px";
            }
            if (height > minimum_size) {
              element.style.height = height + "px";
              element.style.top =
                original_y + (e.pageY - original_mouse_y) + "px";
            }
          }
        }

        function stopResize() {
          window.removeEventListener("mousemove", resize);
        }
      }
    }

    makeResizableDiv(".resizable");

    // CONEXION PEER
    var peer_id;
    var username;
    var conn;

    /**
     * Important: the host needs to be changed according to your requirements.
     * e.g if you want to access the Peer server from another device, the
     * host would be the IP of your host namely 192.xxx.xxx.xx instead
     * of localhost.
     *
     * The iceServers on this example are public and can be used for your project.
     */
    var peer = new Peer({
      secure: true,
      host: "telemedicina.integraweb.com.co",
      port: 9000,
      path: "peerjs",
      debug: 3,
      config: {
        iceServers: [
          { url: "stun:stun.integraweb.com.co:5349" },
          {
            url: "turn:turn.integraweb.com.co:5349",
            credential: "EhjMQtRFvPsLppWh",
            username: "softwaremedico"
          }
        ]
      }
    });

    // Once the initialization succeeds:
    // Show the ID that allows other user to connect to your session.
    peer.on("open", function() {
      document.getElementById("peer-id-label").innerHTML = peer.id;
      id_teleme = peer.id;
    });

    // When someone connects to your session:
    //
    // 1. Hide the peer_id field of the connection form and set automatically its value
    // as the peer of the user that requested the connection.
    // 2.
    peer.on("connection", function(connection) {
      conn = connection;
      peer_id = connection.peer;

      // Use the handleMessage to callback when a message comes in
      conn.on("data", handleMessage);

      // Hide peer_id field and set the incoming peer id as value
      document.getElementById("peer_id").className += " hidden";
      document.getElementById("peer_id").value = peer_id;
      document.getElementById("connected_peer").innerHTML =
        connection.metadata.username;
    });

    peer.on("error", function(err) {
      alert("Error en la Conexion: " + err);
      console.error(err);
    });

    /**
     * Handle the on receive call event
     */
    peer.on("call", function(call) {
      var acceptsCall = confirm("Video llamada entrante quieres aceptarla 1?");

      if (acceptsCall) {
        // Answer the call with your own video/audio stream
        call.answer(window.localStream);
        
        // Receive data
        call.on("stream", function(stream) {
          // Store a global reference of the other user stream
          window.peer_stream = stream;
          // Display the stream of the other user in the peer-camera video element !
          onReceiveStream(stream, "peer-camera");
        });
        $("#camara-doctor").show();
        $("#chat").show();
     
        // Handle when the call finishes
        call.on("close", function() {
          alert("La videollamada a Finalizado");
        });

        // use call.close() to finish a call
      } else {
        console.log("Llamada Rechazada!");
      }
    });

    /**
     * Starts the request of the camera and microphone
     *
     * @param {Object} callbacks
     */
    function requestLocalVideo(callbacks) {
      // Monkeypatch for crossbrowser geusermedia
      navigator.getUserMedia =
        navigator.getUserMedia ||
        navigator.webkitGetUserMedia ||
        navigator.mozGetUserMedia;

      // Request audio an video
      navigator.getUserMedia(
        { audio: true, video: true },
        callbacks.success,
        callbacks.error
      );
    }

    /**
     * Handle the providen stream (video and audio) to the desired video element
     *
     * @param {*} stream
     * @param {*} element_id
     */
    function onReceiveStream(stream, element_id) {
      // Retrieve the video element according to the desired
      var video = document.getElementById(element_id);
      // Set the given stream as the video source
      try {
        video.srcObject = stream;
      } catch (error) {
        video.src = window.URL.createObjectURL(stream);
      }

      // Store a global reference of the stream
      window.peer_stream = stream;
    }

    /**
     * Appends the received and sent message to the listview
     *
     * @param {Object} data
     */
    function handleMessage(data) {
     
        var months = ['Enero','Febrero','Marzo','Abril','Mayo',
                      'Junio','Julio','Agosto','Septiembre',
                      'Octubre','Noviembre','Diciembre'];
         var d = new Date();

        var orientation = "text-right";


      var orientation = "text-right";



        // If the message is yours, set text to right !
        if(data.from == username){
            
            var messageHTML= '<div class="incoming_msg">';
              messageHTML+= '<div class="incoming_msg_img"> <img src="https://ptetutorials.com/images/user-profile.png" alt="sunil"> </div>';
              messageHTML+= '<div class="received_msg">';
              messageHTML+= '<div class="received_withd_msg">';
              messageHTML+= '<p>'+data.text +'</p>';
              messageHTML+= '<span class="time_date">'+d.getHours()+':'+d.getMinutes()+' | '+months[d.getMonth()]+' '+ d.getDay()+' </span>';
              messageHTML+=  '</div></div>';
              messageHTML+=  '</div>';
        }else{
            
            var messageHTML =  '<div class="outgoing_msg">';
                messageHTML += '<div class="sent_msg">';
                messageHTML+= '<p>'+data.text +'</p>';
                messageHTML+= '<span class="time_date">'+d.getHours()+':'+d.getMinutes()+' | '+months[d.getMonth()]+' '+ d.getDay()+' </span>';
                messageHTML+=  '</div></div>';
        }
             document.getElementById("messages").innerHTML += messageHTML;
             $("#messages").scrollTop($("#messages")[0].scrollHeight);
    }

    /**
     * Handle the send message button
     */
    document.getElementById("send-message").addEventListener(
      "click",
      function(e) {
        e.preventDefault();
        // Get the text to send
        var text = document.getElementById("message").value;

        // Prepare the data to send
        var data = {
          from: username,
          text: text
        };

        // Send the message with Peer
        conn.send(data);

        // Handle the message on the UI
        handleMessage(data);

        document.getElementById("message").value = "";
      },
      false
    );

    /**
     *  Request a videocall the other user
     */
    document.getElementById("call").addEventListener(
      "click",
      function(e) {
        alertify.success("Llamando.....");
        document.getElementById("call").disabled = true;

        $("#camara-doctor").show();
         $("#chat").show();
        setTimeout(function() {
          document.getElementById("call").disabled = false;
        }, 5000);
        e.preventDefault();
        console.log("Calling to " + peer_id);
        console.log(peer);

        var call = peer.call(peer_id, window.localStream);

        call.on("stream", function(stream) {
          window.peer_stream = stream;

          onReceiveStream(stream, "peer-camera");
        });
      },
      false
    );

    /**
     * On click the connect button, initialize connection with peer
     */
    document.getElementById("connect-to-peer-btn").addEventListener(
      "click",
      function(e) {
        e.preventDefault();
        username = document.getElementById("name").value;
        peer_id = document.getElementById("peer_id").value;

        if (peer_id) {
          conn = peer.connect(peer_id, {
            metadata: {
              username: username
            }
          });

          conn.on("data", handleMessage);
        } else {
          alert("Hubo un problema en la conexion con el servidor!");
          return false;
        }

        document.getElementById("chat").className = "";
        document.getElementById("connection-form").className += " hidden";
      },
      false
    );

    /**
     * Initialize application by requesting your own video to test !
     */
    requestLocalVideo({
      success: function(stream) {
        window.localStream = stream;
        onReceiveStream(stream, "my-camera");
      },
      error: function(err) {
        alert("No se pudo acceder A tu cámara y microfono!");
        console.error(err);
      }
    });

    // Guardar video
    var recorder;
  
    document.getElementById("btn-start-recording").addEventListener(
      "click",
      function(e) {
        console.log('estoy en el start');
        e.preventDefault();
        alertify.success("La grabación ha comenzado");

        document.getElementById("btn-start-recording").disabled = true;

        // \;codecs=h264
      
        var arrayOfStreams = [window.localStream, window.peer_stream];
          recorder = new MultiStreamRecorder(arrayOfStreams, {
          type: "video",
          mimeType: "mp4",
          recorderType: MediaStreamRecorder,
          timeSlice: 1000,
          bitsPerSecond: 128000,
          videoBitsPerSecond: 128000
        });
         recorder.record();
       

        document.getElementById("btn-stop-recording").disabled = false;
      },
      false
    );

    document.getElementById("btn-stop-recording").addEventListener(
      "click",
      function(e) {
        e.preventDefault();
        alertify.success("La grabación ha finalizado");

        this.disabled = true;
        
        recorder.stop(function(blob) {
        
        var blobURL = URL.createObjectURL(blob);
            DescargaVideo(blobURL);
        
   
});
      },
      false
    );
    
   

    const screenshotbutton = document.getElementById("btn-screenshot");
    const canvas = document.getElementById("canvas-telemedicina");

    screenshotbutton.addEventListener(
      "click",
      function(ev) {
        ev.preventDefault();
        takepicture();
      },
      false
    );

    function takepicture() {
      canvas.width = 300;
      canvas.height = 300;
      canvas
        .getContext("2d")
        .drawImage(document.getElementById("peer-camera"), 0, 0, 300, 300);
    }

    document.getElementById("message").addEventListener(
      "keydown",
      function(e) {
        console.log('estoy en el message');
        let tecla = e.which || e.keyCode;
        if (tecla == 13) {
          // Get the text to send
          var text = document.getElementById("message").value;

          // Prepare the data to send
          var data = {
            from: username,
            text: text
          };

          // Send the message with Peer
          conn.send(data);

          // Handle the message on the UI
          handleMessage(data);

          document.getElementById("message").value = "";
        }
      },
      false
    );
  },
  false
);
