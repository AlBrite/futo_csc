
function popup_alert(obj) {
  const popup = $('.popup-alert');


  let type = obj.type;
  const icons = {
    success: 'task_alt',
    error: 'error',
    warning: 'warning',
    info: 'info',
    message: 'feedback',
    danger: 'dangerous'
  };
  const colors = {
    success: 'green',
    error: 'red',
    warning: 'orange',
    info: 'blue',
    message: 'zinc',
    danger: 'red'
  };
  if (!(type in colors)) {
    type = 'message';
  }
  const color = colors[type];
  const icon = icons[type];

  const classList = popup.attr('class');
  const matchBG = classList.match(/\bb-([a-z0-9\-]+)\b/);
  const matchTEXT = classList.match(/\btext-([a-z0-9\-]+)\b/);

  if (matchTEXT) {
    popup.removeClass(matchTEXT);
  }
  if (matchBG) {
    popup.removeClass(matchBG);
  }
  popup.addClass(`bg-${color}-100`);
  popup.addClass(`text-${color}-600`);

  popup.empty();
  popup.append($('<span>').addClass('material-symbols-rounded').text(icon));
  popup.append($('<span>').text(obj.message));

}
window.popup_alert = popup_alert;

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

window.offOverlay = (time = 500) => {
  const overlay = id('overlay');

  if (overlay) {
    setTimeout(() => {
      
      id('overlay').style.display = 'none';
    
    }, time);
  }
}

window.onresize = () => {
 // window.location.href = window.location.href;
}



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
          const destinationFooter = document.querySelector('#footer-slot');
          console.log(footer, destinationFooter);
      
          if (destinationFooter && footer) {
            destinationFooter.innerHTML = '<span id="working"></span>';
            
           

            htmlDoc.querySelectorAll('#footer-slot script[src]').forEach(element => {
      
              const script = document.createElement('script');
              script.type = 'module';
              script.src = element.getAttribute('src');
              
              destinationFooter.appendChild(script);
    
      
            });

          }

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

document.querySelectorAll('ax[href]').forEach(element => {
  element.addEventListener('click', e => {
    e.preventDefault();
    const route = element.getAttribute('href');

    updatePageContent(route);
  });
});



jQuery(document).ready(function(){
  var $ = jQuery;
    
  function load() {
    $('.scrollerx').each(function(){
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

$(document).on('click', '.sidebar-toggler', function(e){
  const menu = $('.sidebar').eq(0);
  const display = menu.css('display')
  if (window.innerWidth > 1024) {
   if (!menu.is('.minimized')) {
     menu.toggleClass('activate-hovering'); 
   }
    menu.toggleClass('closed-sidebar')
  
  }
  else {
   if (display == 'none') {
     menu.css('display','flex');
   } else {
     menu.hide();
   }
  }
});


$(document).on('mouseenter mouseleave', '.sidebar.activate-hovering', function(e) {
  $(this).toggleClass('closed-sidebar');
})

$(document).on('click', '.toggle-profile-card', function(){
  $(this).toggleClass('show');
})

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

$(document).on('click', '.popup', function(event) {
  const dismiss = $(this).find('.popup-dismiss, input[type=submit], input[type=button], button');

  if ($(event.target).is('.popup') && dismiss.length > 0) {
    dismiss.click();
  }
})

window.onbeforeunload = ()=>{
    onOverlay();
}

$(function(){
  // $(".sidebar .has-menu").on("click", function(e) {
    
  //   $(".sidebar .has-menu").not(this).removeClass('active');
  //   $(this).toggleClass('active');

  // });
  $(".sentence-case").each(function() {
      var text = $(this).text().trim().toLowerCase();
      $(this).text(text.charAt(0).toUpperCase() + text.slice(1));
  });
});

$(document).ready(() => {
  const lastHeights = {};
  const debounceTime = 100; // Default debounce time
/*
var element = document.getElementById('element');
    var styles = window.getComputedStyle(element);
    var paddingTop = parseFloat(styles.paddingTop);
    var paddingBottom = parseFloat(styles.paddingBottom);
    var marginTop = parseFloat(styles.marginTop);
    var marginBottom = parseFloat(styles.marginBottom);
    var borderHeight = element.clientHeight - element.offsetHeight;
*/
  function adjustElementHeight() {
     

      $('.scroller').each(function(index) {
        const $element = $(this);
        const elementOffsetTop = $element.offset().top;
        const screenHeight = $(window).innerHeight();
        const styles = window.getComputedStyle($element[0]);
        var paddingTop = parseFloat(styles.paddingTop);
        var paddingBottom = parseFloat(styles.paddingBottom);
        var marginTop = parseFloat(styles.marginTop);
        var marginBottom = parseFloat(styles.marginBottom);
        var borderHeight = $element[0].clientHeight - $element[0].offsetHeight;
        let maxHeight = screenHeight - elementOffsetTop - borderHeight;
        // - marginTop - marginBottom - paddingBottom - paddingTop;
        
        
        if ($(this).is('.test')) {
          alert('Border'+maxHeight);
        }
        

        // Only set max-height if it has changed
        if (maxHeight !== lastHeights[index]) {
            $element.css({
                'max-height': maxHeight + 'px',
                'transition': 'max-height 0.3s ease' // Smooth transition
            });
            lastHeights[index] = maxHeight;
        }
    });

  }

  setTimeout(() => {
    adjustElementHeight();
  }, 100);

  // Debounce resize event for performance
  let timeout;
  $(window).resize(function() {
      clearTimeout(timeout);
      timeout = setTimeout(adjustElementHeight, debounceTime);
  });
});

$('.h-availx, .h-center').each(function(e){
  setTimeout(()=>{
    const offset = $(this).offset();
    const averiageHeight = Math.ceil($(this).innerHeight()) / 3;
    $(this).css({
      '--avail-screen': `${offset.top}px`,
      '--average-height': `${averiageHeight}px`,
      'visibility': 'visible'
    })
  }, 1000)
});
$(function(){
  let tips = [];
  const seen = false;
  let current = 0;
  
  $('[tips]').each(function(i) {
    tips.push($(this));
  });

  const all = $('[tips]');
  const showTip = function(index = 0) {
    const current = $(`[tips]:eq(${index})`);
    $('.tip-overlay').remove();


    if (current.length > 0) {
      
      let tipOverlay = $('<div>').addClass('tip-overlay');
      let tip = $('<div>').addClass('tip');
      let tipBody = $('<div>').addClass('tip-body').text(current.attr('tips'));
      let tipHeader = $('<div>').addClass('tip-header');
      tipHeader.append($('<span>').text('Tips'));
      tipHeader.append($('<span>').text(`${index+1} of ${all.length}`));
      let tipFooter = $('<div>').addClass('tip-footer');
      let tipActions = $("<div>").addClass('tip-actions');
      let isLast = (index+1) == all.length;
      
      let skip = $('<span>').addClass('tip-skip').addClass('tip-action').text('Skip');
      let next = $('<span>').addClass('tip-next').addClass('tip-action').text(isLast ?'Finish':'Next');
      next.toggleClass('tip-last', isLast);
      skip.toggleClass('invisible', isLast);
  
      tipActions.append(skip);
      tipActions.append(next);
      tipFooter.append(tipActions);

      skip.on('click', function(e) {
        
      })
      next.on('click', function(e) {
        let count = index + 1; 
        current.removeClass('activate-tip');
        showTip(count);
      
      })

      tip.append(tipHeader);
      tip.append(tipBody);
      tip.append(tipFooter);

      current.addClass('tip-disabled');
      const position = current.position();
      const bottom = current.position().top + current.innerHeight();
      const right = current.position().left + current.innerWidth();

      current.addClass('activate-tip');
      tip.css({top:bottom + 10, left:position.left});




      tipOverlay.append(tip);
      $('body').append(tipOverlay);
      

    }
  }

  if (all.length > 0) {
    $('#page-tips').show();
  }
  $(document).on('click', '#page-tips', function(e){
    $('#page-tips').hide();
    showTip(0);
  });


  $(document).on('contextmenu', '.prepare.loading-skeleton', function(e) {
    const skeletons = $('.skeleton', this);
    const mainWidth = $(this).innerWidth();

    if (skeletons.css('display') === 'block') {
      skeletons.css('display', 'inline-block');
    }

    skeletons.each(function(e) {
      //const width = Math.floor(($(this).innerWidth()/mainWidth) * 100);
      const width = $(this).outerWidth();
      $(this).addClass(`w-[${width}px]`);
      $(this).text('');
    })

    
  })


  $("[class*='sticky']").each(function(){
    const hasFixed = /\b(top|bottom)-([a-zA-Z0-9]+)\b/.test($(this).attr('class'));
    const positionTop = $(this).position().top;
    if (!hasFixed && positionTop === 0) {

      $(this).css({
        top: '0px'
      });
      
    }
  });



  $(document).on('input', '[data-session]', function(e) {
    const val = $(this).val();
    const increment = parseInt($(this).data('session'));
    const last = val.charAt(val.length - 1);
   
    var value = val.replace(/[^0-9\/]/, '');
    let year = parseInt(value);
    const len = value.length;
    if (e.originalEvent?.inputType === 'insertText' && (last === '/' && /^\d+\/$/.test(val) || len === 4)) {
      value += "/";
      value += year + increment;
    }
    else if (e.originalEvent?.inputType === 'deleteContentBackward') {
      value = value.replace(/(^|\/)\d+$/, '');

    }
    
    $(this).val(value.replace(/[\/]+/, '/').slice(0, 9));
    
    if (value.length === 9) {
      //$(this).blur();
      $(this).closest('form').find('input:eq(' + ($(this).index() + 1) + ')').focus();
    }
  });

  $('[data-session]').focus(function(e) {
    var input = $(this).get(0);
    input.setSelectionRange(input.value.length, input.value.length);
  });
  $('[data-session]').on("paste", function (event) {
    event.preventDefault();
    const increment = parseInt($(this).data('session'));

    const pastedData = event.originalEvent.clipboardData.getData("text");
    const matcher = pastedData.trim().match(/^(\d+)(\/(\d+))?$/);
    if (matcher) {
      const [ , start, separator, end] = matcher;
      const start_year = parseInt(start);
      const end_year = parseInt(end || start_year + increment);
      
      if ((end_year - start_year) === increment) {
        $(this).val(`${start_year}/${end_year}`);
        
        $(this).closest('form').find('input:eq(' + ($(this).index() + 1) + ')').focus();
      }

    }

  });

  $('[data-session]').on('change', function(e) {
    const value = $(this).val();
    const increment = parseInt($(this).data('session'));
    const matcher = value.trim().match(/^(\d+)(\/(\d+))?$/);
    if (matcher) {
      const [ , start, separator, end] = matcher;
      const start_year = parseInt(start);
      const end_year = parseInt(end || start_year + increment);
      
      if ((end_year - start_year) === increment) {
        $(this).val(`${start_year}/${end_year}`);
        
        $(this).closest('form').find('input:eq(' + ($(this).index() + 1) + ')').focus();
      }
      else {
        $(this).val('');
        $(this).focus();
      }
    }
  });

  

})

