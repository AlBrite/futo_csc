export function extend(name, callback) {
    if (typeof name === "object" && name && name.constructor === Object) {
        Object.keys(name).forEach(function (key) {
            callback = name[key];
            if (typeof callback === "function") {
                window[key] = callback;
            }
        });
        return;
    }
    if (typeof callback === "function") {
        window[name] = callback;
    }
}

export function getAttr(event, attribute, stopAfter = null) {
    if (event.target.getAttribute(attribute)) {
        return event.target.getAttribute(attribute);
    }

    let element = event.target.parentNode;
    let counter = 0;

    while (!element.getAttribute(attribute)) {
        element = element.parentNode;
        if (counter === stopAfter) {
            return null;
        }
        counter++;
    }

    return element.getAttribute(attribute);
}

export class Location {
    constructor() {}

    static get(name, defaultValue = null) {
        const currentUrl = new URL(window.location.href);
        let queryParams = currentUrl.searchParams;
        return queryParams.get(name) ?? defaultValue;
    }
    static drop(name) {
        const currentUrl = new URL(window.location.href);
        let queryParams = currentUrl.searchParams;
        queryParams.delete(name);
        window.history.pushState({}, "", currentUrl.toString());
    }

    static set(obj) {
        Location.push(null, obj);
    }


    static url(url) {
        url = url || window.location.href;
        if (/https?:\/\//.test(url)) {
            return new URL(url);
        }
        return new URL(url, window.location.href);
        return page.toString();
    }

    static push(url, params) {
        const currentUrl = this.url(url);
        let queryParams = currentUrl.searchParams;

        if (params) {
            Object.keys(params).forEach((param) => {
                let value = params[param];

                if (queryParams.has(param)) {
                    queryParams.set(param, value);
                } else {
                    queryParams.append(param, value);
                }
            });
        }
        window.history.pushState({}, "", currentUrl.toString());
    }
    static replace(url, params) {
        const currentUrl = this.url(url);
        let queryParams = currentUrl.searchParams;

        if (params) {
            Object.keys(params).forEach((param) => {
                let value = params[param];

                if (queryParams.has(param)) {
                    queryParams.set(param, value);
                } else {
                    queryParams.append(param, value);
                }
            });
        }
        window.location.href = currentUrl.toString();
    }

    static load(page, data, selector) {
        let url = new URL(`/popups/${page}`, window.location.href);
        const params = url.searchParams;
        Object.keys(data).forEach(key => {
          params.append(key, data[key]);
        });
       
      
        url = url.toString();
        
      
          var xhr = new XMLHttpRequest();
          xhr.onreadystatechange = function () {
              if (xhr.readyState === XMLHttpRequest.DONE) {
                  if (xhr.status === 200) {
                      // Extract the content from the fetched data
                      var parser = new DOMParser();
                      var htmlDoc = parser.parseFromString(
                          xhr.responseText,
                          "text/html"
                      );
                      const destination = document.querySelector(selector||`#${page}`);
                      const source = htmlDoc.querySelector('body');
                      if (destination && source) {
                        Location.set(data);
                        destination.innerHTML = source.innerHTML;
                      }
                  } else {
                    
                  }
              } else {
                console.log(xhr);
              }
          };
          xhr.open("GET", url, true);
          xhr.send();
      
      }
    

}
