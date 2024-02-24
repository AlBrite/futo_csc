
function getOffset(element)
{
    if (!element.getClientRects().length)
    {
      return { top: 0, left: 0 };
    }

    let rect = element.getBoundingClientRect();
    let win = element.ownerDocument.defaultView;
    return (
    {
      top: rect.top + win.pageYOffset,
      left: rect.left + win.pageXOffset
    });   
}

function appendAndChangeLocation(params) {
  const currentUrl = new URL(window.location.href);
  let queryParams = currentUrl.searchParams;

  Object.keys(params).forEach(param => {
      let value = params[param];

      if (queryParams.has(param)) {
          queryParams.set(param, value);
      } else {
          queryParams.append(param, value);
      }
  });

  // Update the URL
  window.history.pushState({}, '', currentUrl.toString());
}
window.appendAndChangeLocation = appendAndChangeLocation;

async function getCSRFToken() {
  const response = await fetch('/api/csrf-end-point');
  const data = await response.json();
  let token = data.csrf_token;
  if (token) {
    return token;
  }
  
  const meta = document.querySelector('meta[name="csrf_token"]');
  if (meta) {
    token = meta.getAttribute('content');
  }
  
  return token;
}

async function api(page, data, init) {

  init = {
    method: 'POST',
    cache: 'no-cache',
    headers: {'Content-Type': 'application/json', 'Accept':'applicatin/json'},
    ...init || {}
  };

  let url = `/api${page}`;

  if ('type' in init) {
    init.method = init.type;
    delete init.type;
  }

  const csrfToken = await getCSRFToken();
  if (csrfToken && init.method === 'POST') {
    init.headers['X-CSRF-TOKEN'] = csrfToken;
  }



  if (['GET','HEAD'].includes(init.method) && data) {
    
    const newUrl = new URL(url, window.location.href);
    const params = newUrl.searchParams;
    for (const query in data) {
      params.append(query, data[query]);
    }
    url = newUrl.toString();
  } else {
    init.body = JSON.stringify(data)
  }

  return new Promise((resolve, reject) => {


    if(typeof localStorage !== 'undefined' && localStorage !== null && localStorage.getItem('access_token')) {
   //   init.headers.Authorization = `Bearer ${localStorage.getItem('access_token')}`;
    }
    
    fetch(url, init)
    .then(response => {

      if (!response.ok) {
        throw new Error('Network response was not ok');
      }
     
      return response.json();
    })
    .then(data => {
      if (['/login', 'register'].includes(page) && 'access_token' in data) {
        localStorage.setItem('access_token', data.access_token);
      }
      else if (page === '/logout') {
        localStorage.removeItem('access_token');
      }
      resolve(data);
    })
    .catch(error => {
      reject(error);
    })
  });

}
window.api = api;

function api_get(url, data, init={}) {
  init.type = 'GET';
  return api(url, data, init);
}
window.api_get = api_get;

function id(el) {
  return document.getElementById(el);
}
window.id = id;

function loadScroller() {
  
    $(document).ready(function(){
      $('.scroller').each(function(){
        const top = $(this).position().top;
        console.log({top});
        $(this).css({
          '--top-offset': top + 'px',
        })
      });
    });
  
  
 
  // document.addEventListener("DOMContentLoaded", function() {
  //   setTimeout(()=>{

  //     document.querySelectorAll('.scroller').forEach(element => {
  //       const coordinates = getOffset(element);
  //       const top =  element.offsetTop;
  //       alert(top);
  
  //       element.style.setProperty('--top-offset', `${top}px`);
  //     });
      
  //   })
  // });
}

window.addEventListener('resize', () => {

  loadScroller();
  
});
window.loadScroller = loadScroller;