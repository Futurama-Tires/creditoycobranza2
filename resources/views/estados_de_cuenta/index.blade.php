@extends('layouts.app')

@section('title', 'Estados de cuenta')

@section('content')
    @livewire('estados_de_cuenta.estados-cuenta')
@endsection

@section('scripts')
    {{-- <script>
        document.addEventListener("DOMContentLoaded", function() {
            new TomSelect("#select-beast-empty", {
                placeholder: "Selecciona un cliente",
                maxItems: 1,
                allowEmptyOption: true,
                create: false,
                dropdownParent: 'body',
                valueField: 'customer_id', // Usar el ID como valor
                labelField: 'altname', // Usar altname para mostrar
                searchField: 'altname', // Buscar por altname
                load: function(query, callback) {
                    $.ajax({
                        url: "/netsuite/get-customers",
                        data: {
                            query: query
                        },
                        success: function(response) {
                            console.log('Data received:', response);

                            // Verificar si la respuesta tiene la estructura esperada
                            if (response.items && Array.isArray(response.items)) {
                                // Mapear los items al formato que TomSelect espera
                                const formattedData = response.items.map(item => ({
                                    customer_id: item.customer_id,
                                    altname: item.altname
                                }));

                                console.log('Formatted data:', formattedData);
                                callback(formattedData);
                            } else {
                                console.error('Unexpected response structure');
                                callback([]);
                            }
                        },
                        error: function(xhr, status, error) {
                            console.error('Error:', error);
                            callback([]);
                        }
                    });
                },
                render: {
                    option: function(item, escape) {
                        return '<div>' + escape(item.altname) + '</div>';
                    },
                    item: function(item, escape) {
                        return '<div>' + escape(item.altname) + '</div>';
                    }
                },
                maxOptions: 100,

                onInitialize: function() {
                    console.log('TomSelect Initialized');
                }
            });
        });
    </script> --}}
    {{-- Saldos Vencidos --}}
    <script>
        const ctx = document.getElementById('saldosVencidos');

        new Chart(ctx, {
            type: 'doughnut',
            data: {
                labels: [
                    '1-30',
                    '31-60',
                    '61-90',
                    '91-120',
                    '>120'
                ],
                datasets: [{
                    label: 'My First Dataset',
                    data: [300, 50, 100, 30, 40],
                    backgroundColor: [
                        'rgb(127, 28, 145)',
                        'rgb(42, 163, 33)',
                        'rgb(209, 215, 11)',
                        'rgb(17, 42, 236)',
                        'rgb(220, 36, 16)',
                    ],
                    hoverOffset: 4
                }]
            },
        });
    </script>




@endsection
