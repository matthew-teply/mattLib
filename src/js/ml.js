// Creating mattLib class, to contain all mattLib JS
function mattLib(web_server, current_app) {

    // Abstraction layer for ajax
    this.ajax = {

        // Listen to ajax events
        listen: (method_name, callback_func) => {
            document.addEventListener('ajax-return', e => {
                if(e.detail.method_name == method_name)
                    callback_func(e);
            });
        },

        // Send ajax request, send out an event on success
        send: (method_name, method_data, request_type="POST", script_url="") => {
            $.ajax({
                url: web_server + script_url,
                method: request_type,
                data: {ajax_subm:true, method_data, method_name},
                error: (xhr, status, error) => {
                    console.error(```
                        mattLib ajax error\n
                        ------------------\n
                        xhr: ``` + xhr.statusText + ```\n
                        status: ``` + status + ```\n
                        error: ``` + error + ```\n
                    ```);
                },
                success: response => {
                    try {
                        response = JSON.parse(response)
                    } catch (e) {}
    
                    // Emit a custom event
                    var ajax_event = new CustomEvent("ajax-return", {
                        detail: {
                            method_name, // Method name
                            data: response // Data returned
                        }
                    });
    
                    // Dispatch event globally to the document
                    document.dispatchEvent(ajax_event);
                }
            });
        },

        request: (url, method, data, callback_success, callback_error = err => {
            console.error("mattLib ajax request error!");
            console.error(err);
        }) => {
            $.ajax({
                async: true,
                url: web_server + current_app + url,
                method,
                data,
                success: (response) => callback_success(response),
                error: (xhr, status, error) => callback_error(xhr, status, error)
            });
        }

    }

    this.redirect = (route) => {
        window.location.replace(web_server + current_app + route);
    }

    this.modifyHref = () => {
        $("a").each(function() {
            $(this).attr("href", web_server + current_app + $(this).attr("href"));
        });
    
        $("form").each(function(index, form) {
            if(form.classList.contains("ml-ajax-form"))
                return;
    
            $(this).attr("action", web_server + current_app + $(this).attr("action"));
        });
    }
}
