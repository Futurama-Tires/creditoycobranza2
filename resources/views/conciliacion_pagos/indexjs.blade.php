@section('scripts')
    <script>
        let table = new DataTable('#myTable', {
            pageLength: 5,
            order: []
        });

        let hola = new DataTable('#myTable2', {
            pageLength: 5,
            scrollX: true, // <-- Important

            //columns: [null, null, null, null, null, { width: '90%' }, null, null, null, null, null, null, null, null, null, null, null, null],
            columnDefs: [
                { width: '300px', targets: 5 } // 0-based index, so 5 = "Nota"
            ],
            order: [],
        });


    </script>

    <!-- <script>
                                                    // Hide loader when everything has loaded
                                                    window.addEventListener('load', function () {
                                                        const loader = document.getElementById('loader');
                                                        if (loader) {
                                                            loader.style.display = 'none';
                                                        }
                                                    });
                                                </script> -->
@endsection