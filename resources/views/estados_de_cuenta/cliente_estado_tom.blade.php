<style>
    /* Increase modal width if needed */
    .modal-dialog {
        display: flex;
        justify-content: center;
        align-items: center;
        min-height: 100vh;
        max-width: 1000px;
        /* Adjust this if you need a wider modal */
    }

    /* Custom styles to increase the width of TOM Select */
    .tom-select .select-input {
        width: 100% !important;
        /* Full width of the parent container */
        max-width: 700px;
        /* You can adjust this value to make it wider */
        /* Optional: You can also set a specific width */
        /* width: 600px !important; */
    }

    /* If you want the select element to be wider within the modal container */
    #select-beast-empty {
        width: 100% !important;
        /* Full width of the modal content */
    }
</style>
<link href="https://cdn.jsdelivr.net/npm/tom-select@2.4.3/dist/css/tom-select.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/tom-select@2.4.3/dist/js/tom-select.complete.min.js"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<div class="modal modal-blur fade dialog-centered" id="modal-consultar_estado" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
        <form action="" method="POST" id="addResponsivaForm">
            @csrf
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Agregar Responsiva</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    <div class="errMsgContainer">

                    </div>
                </div>
                <div class="modal-body">
                    <div class="row mb-12">

                        <div class="mb-3">
                            <label class="form-label required">Seleccionar Empleado</label>
                            <select id="select-beast-empty" data-placeholder="Buscar Cliente ..." autocomplete="off"
                                style="width: 100%; max-width: 500px;">
                                <option value="">None</option> <!-- Default option -->
                            </select>
                        </div>

                    </div>
                </div>


                <div class="modal-footer">
                    <a href="#" class="btn btn-link link-secondary btn-3" data-bs-dismiss="modal">
                        Cancel
                    </a>
                    <button type="submit" class="btn btn-primary btn-5 ms-auto add_responsiva">
                        <!-- Download SVG icon from http://tabler.io/icons/icon/plus -->
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                            fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                            stroke-linejoin="round" class="icon icon-2">
                            <path d="M12 5l0 14" />
                            <path d="M5 12l14 0" />
                        </svg>
                        Buscar Cliente
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
    new TomSelect("#select-beast-empty", {
        allowEmptyOption: true,  // Allow empty option for testing
        create: false,
        load: function(query, callback) {
            $.ajax({
                url: "/netsuite/get-client-names",
                data: {
                    query: query
                },
                success: function(data) {
                    console.log('Data received:', data);  // Verify the raw data

                    // Clean the data: remove surrounding quotes and trim spaces
                    const formattedData = data.map(function(name) {
                        // Remove surrounding quotes and trim spaces
                        const cleanedName = name.replace(/^"|"$/g, '').trim();
                        return { 
                            id: cleanedName,    // Use the cleaned name as id
                            text: cleanedName,  // Display this in the dropdown
                            value: cleanedName  // This will be the value of the option
                        };
                    });

                    console.log('Formatted data:', formattedData); // Verify formatted data
                    callback(formattedData);  // Send the formatted data to TomSelect
                }
            });
        },
        maxOptions: 100,
        placeholder: "Buscar Cliente ...",
        onInitialize: function() {
            console.log('TomSelect Initialized');
        }
    });
</script>
