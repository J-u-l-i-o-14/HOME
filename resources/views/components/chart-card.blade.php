@props(['title', 'type' => 'line', 'data' => [], 'labels' => [], 'id'])

<div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
    <h3 class="text-lg font-medium text-gray-900 mb-4">{{ $title }}</h3>
    <div class="relative h-64">
        <canvas id="{{ $id }}"></canvas>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const ctx = document.getElementById('{{ $id }}').getContext('2d');
    new Chart(ctx, {
        type: '{{ $type }}',
        data: {
            labels: @json($labels),
            datasets: [{
                label: '{{ $title }}',
                data: @json($data),
                borderColor: '#dc2626',
                backgroundColor: 'rgba(220, 38, 38, 0.1)',
                borderWidth: 2,
                fill: true,
                tension: 0.4
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: false
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    grid: {
                        color: 'rgba(0, 0, 0, 0.1)'
                    }
                },
                x: {
                    grid: {
                        color: 'rgba(0, 0, 0, 0.1)'
                    }
                }
            }
        }
    });
});
</script>
@endpush