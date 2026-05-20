 // php mete aquí el timestamp del próximo reinicio (algo como 1748000000)
// multiplicamos por 1000 porque javascript trabaja en milisegundos y php en segundos
// cuenta atrás hasta el próximo personaje
function iniciarContador(reinicio)
{
        function actualizarContador()
        {
            // diferencia entre el reinicio y ahora en ms
            const diff = reinicio-Date.now();
            const h = Math.floor(diff / 3600000);
            const m = Math.floor((diff % 3600000) / 60000);
            const s = Math.floor((diff % 60000) / 1000);
            document.getElementById('contador').textContent = h + 'h ' + m + 'm ' + s + 's';
        }
        // actualizar el contador cada segundo
        setInterval(actualizarContador, 1000);
        actualizarContador();
}