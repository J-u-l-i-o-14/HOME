@props(['headers' => [], 'searchable' => true, 'id' => 'dataTable'])

<div class="card border-0 shadow-sm">
    <div class="card-header bg-white border-bottom">
        <div class="row align-items-center">
            <div class="col">
                <h6 class="mb-0">{{ $title ?? 'Données' }}</h6>
            </div>
            @if($searchable)
            <div class="col-auto">
                <div class="input-group input-group-sm">
                    <span class="input-group-text">
                        <i class="fas fa-search"></i>
                    </span>
                    <input type="text" class="form-control" placeholder="Rechercher..." id="{{ $id }}_search">
                </div>
            </div>
            @endif
        </div>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0" id="{{ $id }}">
                @if(count($headers) > 0)
                <thead class="table-light">
                    <tr>
                        @foreach($headers as $header)
                            <th>{{ $header }}</th>
                        @endforeach
                    </tr>
                </thead>
                @endif
                <tbody>
                    {{ $slot }}
                </tbody>
            </table>
        </div>
    </div>
</div>

@if($searchable)
@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('{{ $id }}_search');
    const table = document.getElementById('{{ $id }}');
    const tbody = table.querySelector('tbody');
    const rows = Array.from(tbody.querySelectorAll('tr'));

    searchInput.addEventListener('input', function() {
        const searchTerm = this.value.toLowerCase();
        
        rows.forEach(row => {
            const text = row.textContent.toLowerCase();
            row.style.display = text.includes(searchTerm) ? '' : 'none';
        });
    });
});
</script>
@endpush
@endif