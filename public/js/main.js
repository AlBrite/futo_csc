

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

function updatePageContent(route) {
fetch(route)
  .then(response => {
    if (!response.ok) {
      throw new Error('Failed to fetch page');
    }
    return response.text();
  })
  .then(text => {
    var parser = new DOMParser();
    var htmlDoc = parser.parseFromString(text,"text/html");
    const destination = document.querySelector('#main-slot');
    const source = htmlDoc.querySelector('#main-slot');
    if (destination && source) {
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
  }).catch(err => alert(err));
}

window.addEventListener('popstate', function(e) {

updatePageContent(window.location.pathname);

});
updatePageContent(window.location.pathname);

document.querySelectorAll('a[href]').forEach(element => {
element.addEventListener('click', e => {
  e.preventDefault();
  const route = element.getAttribute('href');

  window.history.pushState(null, '', route);

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

