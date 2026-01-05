@include('shared.html')
@include('shared.head', ['pageTitle' => 'Zapomniałem hasła'])

@include('shared.navbar')

<div class="container mt-5 text-center">
    <div class="max-w-2xl mx-auto">
        <p class="text-red-500">Przepraszamy, system przypominania hasła jest tymczasowo niedostępny.</p>
        <br>
        <p class="text-red-500">Prosimy o kontakt z administratorem serwisu: <b>klub@sokol.pl</b></p>
    </div>
</div>

@include('shared.footer')
