@extends('site.layout')
@section('title','Carrinho')
    
@section('conteudo')
        
    <div class="container">
        <div class="row">
            <div class="col-12 col-md-4">
                <div class="bg-gradient-green card border-0 p-3">
                    <i class="bi bi-currency-dollar"></i>
                    <p>Faturamento</p>
                    <h3>R$ 123,00</h3>
                </div>
            </div>
            <div class="col-12 col-md-4">
                <div class="bg-gradient-blue card border-0 p-3">
                    <i class="bi bi-person"></i>
                    <p>Usuários</p>
                    <h3>{{ $usuarios }}</h3>
                </div>
            </div>
            <div class="col-12 col-md-4">
                <div class="bg-gradient-orange card border-0 p-3">
                    <i class="bi bi-cart"></i>
                    <p>Pedidos este mês</p>
                    <h3>0</h3>
                </div>
            </div>
        </div>
    </div>

    <div class="row container">
        <section class="graficos col-12 col-md-6">
            <div class="grafico card border-0">
                <h5 class="text-center">Aquisição de usuários</h5>
                <canvas id="myChart" width="400" height="200"></canvas>
            </div>
        </section>
        <section class="graficos col-12 col-md-6">
            <div class="grafico card border-0">
                <h5 class="text-center">Produtos</h5>
                <canvas id="myChart2" width="400" height="200"></canvas>
            </div>
        </section>
    </div>

    <script src="{{ asset('js/chart.js') }}"></script>

    <script>
        /* Gráfico 01 */
        var ctx = document.getElementById('myChart');
        var myChart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: [{{ $userAno }}],
                datasets: [{
                    label: [{!! $userLabel !!}],
                    data: [{{ $userTotal }}],
                    backgroundColor: [
                        'rgba(255, 99, 132, 1)',
                        'rgba(54, 162, 235, 1)',
                        'rgba(255, 206, 86, 1)',                         
                        'rgba(255, 159, 64, 1)'
                    ],
                    borderColor: [
                        'rgba(255, 99, 132, 1)',
                        'rgba(54, 162, 235, 1)',
                        'rgba(255, 206, 86, 1)',                     
                        'rgba(255, 159, 64, 1)'
                    ],
                borderWidth: 1, 
                }]
            },
            options: {
                scales: {
                    yAxes: [{
                        ticks: {
                            beginAtZero: true
                        }
                    }]
                }
            }
        });

        /* Gráfico 02 */
        var ctx = document.getElementById('myChart2');
        var myChart = new Chart(ctx, {
            type: 'pie',
            data: {
                labels: [{!! $catLabel !!}],
                datasets: [{
                    label: 'Visitas',
                    data: [{{ $catTotal }}],
                    backgroundColor: [
                        'rgba(255, 99, 132)',
                        'rgba(54, 162, 235)',                         
                        'rgba(255, 159, 64)'
                    ]
                }]
            }
        });

    </script>
@endsection