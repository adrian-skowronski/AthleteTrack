@include('shared.html')
@include('shared.head', ['pageTitle' => 'Zatwierdzanie użytkownika'])
@include('shared.navbar')

<div class="container mt-5 mb-5">
    @include('shared.session-error')
    @include('shared.validation-error')

    <h1>Zatwierdzanie użytkownika</h1>
    <h2>Uzupełnij dane dla: {{ $user->name }} {{ $user->surname }}</h2>

    <form method="POST" action="{{ route('admin.storeApproval', $user->user_id) }}" id="approvalForm">
        @csrf

        {{-- Rola --}}
        <div class="form-group mt-3">
            <label><b>Rola <em>(wymagane)</em></b></label>
            @foreach($roles as $role)
                <div class="form-check">
                    <input class="form-check-input role-radio" type="radio" name="role_id" id="role_{{ $role->role_id }}" value="{{ $role->role_id }}" {{ old('role_id') == $role->role_id ? 'checked' : '' }} required>
                    <label class="form-check-label" for="role_{{ $role->role_id }}">
                        {{ $role->name }}
                    </label>
                </div>
            @endforeach
            @error('role_id')
                <div class="text-danger mt-1">{{ $message }}</div>
            @enderror
        </div>

        {{-- Punkty --}}
        <div class="form-group mt-3" id="pointsField" style="display:none;">
            <label for="points"><b>Punkty <em>(wymagane dla sportowców)</em></b></label>
            <input type="number" id="points" name="points" class="form-control w-auto" value="{{ old('points') }}">
            @error('points')
                <div class="text-danger mt-1">{{ $message }}</div>
            @enderror
        </div>

        {{-- Dyscyplina --}}
        <div class="form-group mt-3" id="sportField" style="display:none;">
            <label><b>Dyscyplina <em>(wymagane dla trenerów i sportowców)</em></b></label><br>
            @foreach($sports as $sport)
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="sport_id" id="sport_{{ $sport->sport_id }}" value="{{ $sport->sport_id }}" {{ old('sport_id') == $sport->sport_id ? 'checked' : '' }}>
                    <label class="form-check-label" for="sport_{{ $sport->sport_id }}">
                        {{ $sport->name }}
                    </label>
                </div>
            @endforeach
            @error('sport_id')
                <div class="text-danger mt-1">{{ $message }}</div>
            @enderror
        </div>

        {{-- Kategoria --}}
        <div class="form-group mt-3" id="categoryField" style="display:none;">
            
        </div>

        <button type="submit" class="btn btn-success mt-3 mb-3">Zatwierdź</button>
    </form>
</div>

@include('shared.footer')

<script>
document.addEventListener('DOMContentLoaded', function() {
    const roleRadios = document.querySelectorAll('.role-radio');
    const pointsField = document.getElementById('pointsField');
    const sportField = document.getElementById('sportField');
    const categoryField = document.getElementById('categoryField');

    function updateFields() {
        const selectedRole = document.querySelector('.role-radio:checked');
        if (!selectedRole) return;

        const roleName = selectedRole.nextElementSibling.textContent.trim().toLowerCase();

        // Ukryj wszystko na start
        pointsField.style.display = 'none';
        sportField.style.display = 'none';
        categoryField.style.display = 'none';

        if (roleName === 'admin') {
            // Admin – nic nie pokazuj
        } else if (roleName === 'trener') {
            // Trener – dyscyplina wymagana
            sportField.style.display = 'block';
        } else if (roleName === 'sportowiec') {
            // Sportowiec – punkty, dyscyplina, kategoria
            pointsField.style.display = 'block';
            sportField.style.display = 'block';
            categoryField.style.display = 'block';
        }
    }

    // Odśwież po załadowaniu strony, np. po walidacji
    updateFields();

    // Zmiana roli
    roleRadios.forEach(radio => {
        radio.addEventListener('change', updateFields);
    });
});
</script>
