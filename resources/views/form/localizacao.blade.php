<!-- resources/views/ocorrencias/form-localizacao.blade.php -->
<div class="" hidden>
    <div class="form-group">
        <label for="latitude">Latitude</label>
        <input type="text" id="latitude" name="latitude" hidden class="form-control" value="{{ old('latitude') }}" />
    </div>
    
    <div class="form-group">
        <label for="longitude">Longitude</label>
        <input type="text" id="longitude" name="longitude" hidden class="form-control" value="{{ old('longitude') }}" />
    </div>
</div>


<!-- Script para capturar a localização -->
<script>
    document.addEventListener('DOMContentLoaded', function() {
        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(function(position) {
                document.getElementById('latitude').value = position.coords.latitude;
                document.getElementById('longitude').value = position.coords.longitude;
            }, function(error) {
                
            });
        } else {
            alert("Geolocalização não é suportada neste navegador.");
        }
    });
</script>
