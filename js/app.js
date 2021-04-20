  /* Get JSON from message and display them correctly*/

function getMessages(){

  // AJAX request to connect to server and manager.php
  const xhr = new XMLHttpRequest();
  xhr.open("GET", "manager.php");

  // Exploit JSON and display them in HTML format
  xhr.onload = function(){
    const result = JSON.parse(xhr.responseText);
    const resultHtml = result.reverse().map(function(message){
      return `
        <div class="message">
          <span class="date">${message.created_at.substring(11, 16)}</span>
          <span class="author">${message.author}</span> : 
          <span class="content">${message.content}</span>
        </div>
      `
    }).join('');

    const messages = document.querySelector('.messages');

    messages.innerHTML = resultHtml;
    messages.scrollTop = messages.scrollHeight;
  }

  // Send request
  xhr.send();
}


  /*Function to post message on server and refresh message*/

function postMessage(e){

  // Stop form's submit
  e.preventDefault();

  // Get form's values
  const author = document.querySelector('#author');
  const content = document.querySelector('#content');

  // Construct a pair of key/values with form's values
  const data = new FormData();
  data.append('author', author.value);
  data.append('content', content.value);

  // 4. Create new request and send data (POST)
  const requeteAjax = new XMLHttpRequest();
  requeteAjax.open('POST', 'manager.php?task=write');
  
  requeteAjax.onload = function(){
    content.value = '';
    content.focus();
    getMessages();
  }

  requeteAjax.send(data);
}

document.querySelector('form').addEventListener('submit', postMessage);

  /* Refresh message every 3s*/
const interval = window.setInterval(getMessages, 3000);

getMessages();