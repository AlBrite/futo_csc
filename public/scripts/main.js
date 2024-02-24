function addEvent(selector, callback, event = 'click') {
  const select = document.querySelectorAll(selector);
  
  select.forEach(function(element) {
    element.addEventListener(event, function(e) {
      callback.call(element, e);
    });
  });
}





function toggleDarkMode() {
  const darkMode = !this.darkMode;
  this.darkMode = darkMode;

  localStorage.setItem('darkMode', darkMode);
  document.cookie = "dark_mode=" + (this.darkMode ? 'true':'false') + "; path=/";
}
window.toggleDarkMode = toggleDarkMode;

function handleResize(){
  const isLarge = window.innerWidth > 1024;
  this.navIsOpen = isLarge;
  this.showInfo = isLarge;
}
window.handleResize = handleResize;


const sidebarSearch = document.querySelector('.sidebar-search');

if (sidebarSearch) {

  sidebarSearch.addEventListener('click', (e) => {
    const menu = document.querySelector('.sidebar-menu');
    const input = document.querySelector('.-sidebar-search-input');
    input.focus();
     menu.classList.add('full-menu'); 
  });
}


async function generateTranscript($el) {
  const target = $el.target;
  const name = target.getAttribute('data-name');
  const reg_no = target.getAttribute('data-regNo');

  // try {
  //   const data = await api('/student', {id:reg_no});
  //   console.log(data);
  //   //const data = await res.json();
  // } catch(e) {console.log(e);}

  
  id('overlay').style.display = 'none';

  id('transcriptregNum').value = reg_no;
  id('transcriptHolder').value = name;
  id('transcriptgenerator').classList.remove('hidden');

  this.formOpen = true;
}

window.generateTranscript = generateTranscript;
function onOverlay() {
  const overlay = id('overlay');
  if (overlay) {
    id('overlay').style.display = 'flex';
  }
}


setTimeout(() => {
    const overlay = document.getElementById('overlay');
    if (overlay) {
      overlay.style.display = 'none';
    }
}, 100);

function handlePrint() {
  window.print(document.body)
}

window.handlePrint = handlePrint;

function isLocal(route) {
  return (/https?:\/\//.test(route) && route.startWith(window.location.origin)) || !/https?:\/\//.test(route);
}





function updatePageContent(route) {
  if (isLocal(route)) {
    fetch(route)
      .then(response => {
        if (!response.ok) {
          throw new Error(response.statusText);
        }
        return response.text();
      })
      .then(text => {
        var parser = new DOMParser();
        var htmlDoc = parser.parseFromString(text,"text/html");
        const destination = document.querySelector('#main-slot');
        const source = htmlDoc.querySelector('#main-slot');
        if (destination && source) {
          window.history.pushState(null, '', route);
          destination.innerHTML = source.innerHTML;

          const attrs = Array.from(htmlDoc.querySelector('html').attributes);
          
          attrs.forEach(attr => {
            const value = attr.nodeValue;
            const name = attr.name;
            destination.querySelector('html')?.setAttribute(name, value);
          });
          const footer = htmlDoc.querySelector('#footer-slot');
          htmlDoc.querySelectorAll('#footer-slot script[src]').forEach(element => {

            const script = document.createElement('script');
            script.type = 'module';
            script.src = element.getAttribute('src');
            footer.appendChild(script);
            script.onload = () => {
                alert('11')
            };

          });

        } 
        else {
          throw new Error('Failed to fetch page');
        }
      }).catch(err => {
       
        const html = document.createElement('div');
        const img = document.createElement('img');
        const wrapper = document.createElement('div');
        const actions = document.createElement('span');
        const reloadLink = document.createElement('a');
        const backLink = document.createElement('a');
        const message = document.createElement('div');
        
        html.setAttribute('id', 'error-overlay');
        img.classList.add('justify-self-center');
        img.src = 'http://127.0.0.1:8000/images/no-course.png';
        actions.classList.add('actions');
        reloadLink.setAttribute('href', window.location.href);
        backLink.setAttribute('href', '/');
        message.innerText = err.message;
        message.classList.add('error-message')
        wrapper.classList.add('error-wrapper');

        reloadLink.innerText = 'Reload';
        backLink.innerText = 'Dashboard'


        actions.appendChild(reloadLink);
        actions.appendChild(backLink);


        wrapper.appendChild(img);
        wrapper.appendChild(message);
        wrapper.appendChild(actions);

        html.appendChild(wrapper);

        const errorOverlay = document.querySelector('#error-overlay');
        if (errorOverlay) {
          errorOverlay.remove();
        }

        document.querySelector('body').appendChild(html);
      });
  } else {
    window.location.href = route;
  }
}

window.addEventListener('popstate', function(e) {

  //updatePageContent(window.location.pathname);

});

document.querySelectorAll('a[href]').forEach(element => {
  element.addEventListener('click', e => {
    e.preventDefault();
    const route = element.getAttribute('href');

    updatePageContent(route);
  });
});



jQuery(document).ready(function(){
  var $ = jQuery;
    
  function load() {
    $('.scroller').each(function(){
      const top = $(this).offset().top;
      const height = window.innerHeight;

      $(this).css({
        '--top-offset': `${top}px`,
      });
    });

  }  
  load();


    // Create a MutationObserver instance
  var observer = new MutationObserver(function(mutations) {
    mutations.forEach(function(mutation) {
      // Check if nodes were added to the DOM
      if (mutation.type === 'childList' && mutation.addedNodes.length > 0) {
        setTimeout(() => {
          load();
        })
      }
    });
  });

  // Configure the observer to watch for changes in the DOM
  var observerConfig = {
    childList: true,
    subtree: true
  };

  //Start observing the document
  observer.observe(document.body, observerConfig);

  $(document).on('click','select.data-load-classes:not(.clicked)', function(e) {
    
    const element = $(this);
    api('/classes')
    .then(res => {
      element.addClass('clicked');
      const first = $(this).find('option');
      res.forEach(set => {
        element.append(`<option value="${set.id}">${set.name}</option>`);
      });
    });
  });


});

const menuToggler = document.querySelector('.sidebar-toggler');

if (menuToggler) {
  menuToggler.addEventListener('click', () => {
   const menu = document.querySelector('.sidebar-menu');
   if (window.innerWidth > 1024) {
     menu.classList.toggle('full-menu'); 
   }
   else {
    if (menu.style.display == 'none') {
      menu.style.display = 'flex';
    } else {
      menu.style.display = 'none';
    }
   }
  });
}

addEvent('fieldset.input', function(evt){
  const target = evt.target || evt.srcElement;
  const placeholder = target.getAttribute('placeholder');
  if (placeholder) {
    target.setAttribute('data-placeholder', placeholder);
    target.removeAttribute('placeholder');
  }
 this.classList.add('focused'); 
}, 'focusin');


addEvent('fieldset.input', function(evt){
  const target = evt.target || evt.srcElement;
  const placeholder = target.getAttribute('data-placeholder');
  if (placeholder) {
    target.setAttribute('placeholder', placeholder);
    target.removeAttribute('data-placeholder');
  }
  this.classList.remove('focused'); 
 }, 'focusout');

addEvent('.click-print', function(event){
    window.print(document.body);

});

