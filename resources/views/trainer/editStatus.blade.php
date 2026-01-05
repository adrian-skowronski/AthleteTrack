@include('shared.html')
@include('shared.head', ['pageTitle' => 'Edycja Statusu Treningu'])

<body>
    @include('shared.navbar')
    @include('shared.validation-error')
    @include('shared.session-error')
    <div class="container mt-5">
        <h1 class="text-center mb-5">Edycja Statusu Treningu</h1>

       <div class="row justify-content-center mb-3">
    <div class="col-md-6">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">
                    <strong>Trening:</strong> {{ $training->description }}
                </h5>
                <h5 class="card-title">
                    <strong>Data:</strong> {{ $training->date }}
                </h5>
                <h5 class="card-title">
                    <strong>Uczestnik:</strong> {{ $user->name }} {{ $user->surname }}
                </h5>
                <h5 class="card-title">
                    <strong>Maksymalna liczba punktów:</strong> {{ $training->max_points }}
                </h5>
            </div>
        </div>
    </div>
</div>

        <div class="row justify-content-center">
    <div class="col-md-6">
        <form action="{{ route('trainer.updateStatus', ['training_id' => $training->training_id, 'user_id' => $user->user_id]) }}" method="POST" class="p-3 border rounded">
            @csrf
            @method('PATCH')
            <div class="mb-3">
                <label for="status" class="form-label">Status</label>
                <select id="status" name="status" class="form-select" required>
                    <option value="obecność" {{ $user->status == 'obecność' ? 'selected' : '' }}>Obecność</option>
                    <option value="nieobecność usprawiedliwiona" {{ $user->status == 'nieobecność usprawiedliwiona' ? 'selected' : '' }}>Nieobecność usprawiedliwiona</option>
                    <option value="nieobecność nieusprawiedliwiona" {{ $user->status == 'nieobecność nieusprawiedliwiona' ? 'selected' : '' }}>Nieobecność nieusprawiedliwiona</option>
                </select>
            </div>
            <div class="mb-3">
    <label for="points" class="form-label">Punkty</label>
    <input type="number" id="points" name="points" class="form-control" value="{{ old('points', $user->status == 'nieobecność usprawiedliwiona' ? '0' : ($user->status == 'nieobecność nieusprawiedliwiona' ? '-5' : $trainingUser->points ?? '')) }}" required max="{{ $training->max_points }}" pattern="\d*" {{ $user->status == 'nieobecność usprawiedliwiona' || $user->status == 'nieobecność nieusprawiedliwiona' ? 'readonly' : '' }}>
   
    <div class="invalid-feedback">Punkty muszą być liczbą całkowitą z zakresu od 0 do {{ $training->max_points }}.</div>
</div>
            <div class="d-flex justify-content-center">
                <button type="submit" class="btn btn-primary">Zapisz</button>
            </div>
        </form>
    </div>
</div>
    </div>
    @include('shared.footer')
</body>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const statusSelect = document.getElementById('status');
        const pointsInput = document.getElementById('points');

        statusSelect.addEventListener('change', function() {
            if (statusSelect.value === 'nieobecność usprawiedliwiona') {
                pointsInput.value = '0';
                pointsInput.setAttribute('readonly', true);
            } else if (statusSelect.value === 'nieobecność nieusprawiedliwiona') {
                pointsInput.value = '-5';
                pointsInput.setAttribute('readonly', true);
            } else {
                pointsInput.removeAttribute('readonly');
            }
        });

        // Inicjalna obsługa
        if (statusSelect.value === 'nieobecność usprawiedliwiona' || statusSelect.value === 'nieobecność nieusprawiedliwiona') {
            pointsInput.setAttribute('readonly', true);
        }
    });
</script>

</html>
