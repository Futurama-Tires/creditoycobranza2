    {{-- Modal para registrar un nuevo finiquito --}}
    <div class="modal modal-blur fade" id="modal-consultar-cliente" data-bs-backdrop="static" data-bs-keyboard="false"
        tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-scrollable" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Buscar cliente</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('getFacturasPagosNDC') }}" method="POST" id="addResponsivaForm">
                        @csrf

                        {{-- Empleado --}}
                        <div class="col">
                            <div class="mb-3">
                                <label class="form-label required">Seleccionar cliente</label>
                                <select class="form-select z-index-2" name="customer_id" id="select-beast-empty"
                                    required></select>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Separated inputs</label>
                                <div class="row g-2">
                                    <div class="col">
                                        <input type="text" class="form-control" placeholder="Search for…">
                                    </div>
                                    <div class="col-auto">
                                        <a href="#" class="btn btn-2 btn-icon" aria-label="Button">
                                            <!-- Download SVG icon from http://tabler.io/icons/icon/search -->
                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                                class="icon icon-2">
                                                <path d="M10 10m-7 0a7 7 0 1 0 14 0a7 7 0 1 0 -14 0"></path>
                                                <path d="M21 21l-6 -6"></path>
                                            </svg>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn me-auto" data-bs-dismiss="modal">Cerrar</button>
                    <button type="submit" class="btn btn-primary">Buscar</button>
                </div>
            </div>
            </form>
        </div>
    </div>
    </div>


    {{-- <div class="modal modal-blur fade dialog-centered" id="modal-consultar_estado" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg " role="document">
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
                                <option value=""></option> <!-- Default option -->
                            </select>
                        </div>
                    </div>
                </div>


                <div class="modal-footer">
                    <a href="#" class="btn btn-link link-secondary btn-3" data-bs-dismiss="modal">
                        Cancel
                    </a>
                    <button type="submit" class="btn btn-primary btn-5 ms-auto add_responsiva">
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
</div> --}}
