const input = document.getElementById('searchInput');
const dropdown = document.getElementById('dropdown');
const hidden = document.getElementById('juegoElegido');

// cuando el usuario escribe en el cuadro de texto
input.addEventListener('input', function()
{
    const q = this.value.toLowerCase();

    // quitar resultados anteriores
    dropdown.innerHTML ='';

    // si está vacío no mostrar la caja de resultados
    if (!q)
    {
        dropdown.style.display='none';
        return;
    }

    // recorrer todos los datos de los juegos para mostrarlos
    for (let i=0;i<datos.length;i++)
    {
        const th = String(datos[i].id);
        
        if (datos[i].nombre.toLowerCase().includes(q) || th.startsWith(q))
        {
            // contenedor
            const div = document.createElement('div');
            
            // imagen
            const img = document.createElement('img');
            img.src = 'img/juegos/' + datos[i].imagen;
            img.width = 30;
            img.height = 30;

            // texto del nombre
            const span = document.createElement('span');
            span.textContent = datos[i].nombre;

            // cuando se coloca el cursor sobre una opción se convierte en un puntero
            div.style.cursor = 'pointer';

            // poner la imagen y el texto en el contenedor
            div.appendChild(img);
            div.appendChild(span);

            // guardar el nombre como atributo para recuperarlo al clicarlo
            div.setAttribute('data-nombre', datos[i].nombre);

            //al clicar una opción guardarlo en un atributo oculto,
            // en la caja de texto y ocultar el cuadro de resultados
            div.addEventListener('click', function()
            {
                input.value = this.getAttribute('data-nombre');
                hidden.value = this.getAttribute('data-nombre');
                dropdown.style.display='none';
            });
            dropdown.appendChild(div);
        }
    }
    // sale listado lo que tenga la caja de resultados si los hay
    dropdown.style.display = 'block';
});

// si se clica algo que no es el input se cierra
document.addEventListener('click', function(e)
{
    if (!e.target.closest('#searchInput') && !e.target.closest('#dropdown'))
        dropdown.style.display = 'none';
});