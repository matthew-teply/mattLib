$(document).ready(function() {
    $(".ml-ajax-form").submit(function(e) {
        e.preventDefault();

        var data_submitted = $(this).serializeArray(); // Raw data, serialized into array
        var method_name = $(this).attr("data-function"); // PHP method's name
        var method_data = []; // Initializing array for method data, that is going to be passed to backend
        var method_url = $(this).attr("action");
        var request_type = $(this).attr("method");

        console.log(data_submitted);

        // Seperate Ajax data and method name
        data_submitted.forEach(data => {
            // Check if sort order is enforced
            if(!data.name.includes("so_"))
                method_data.push(data.value);

            else {
                let sort_order = data.name.split("so_")[1];

                method_data[sort_order] = data.value;
            }
        });

        // Submit data to backend
        ml.ajax.send(method_name, method_data, request_type, method_url);
    });
});