document.addEventListener('DOMContentLoaded', () => {
    const sizeLabels = document.querySelectorAll('.talla-label');
    const colorLabels = document.querySelectorAll('.color-label');
    const subcategoryInputs = document.querySelectorAll('input[name="subcategory"]');

    function applyFilters() {
        const selectedSize = document.querySelector('.talla-label input:checked')?.value || '';
        const selectedColor = document.querySelector('.color-label input:checked')?.value || '';
        const selectedSubcategory = document.querySelector('input[name="subcategory"]:checked')?.value || '';
        const categoria = document.querySelector('#categoria').value;

        const ajaxUrl = base_url + '/Productos/filtrarProductos';
        let request = new XMLHttpRequest();
        request.open("POST", ajaxUrl, true);
        request.setRequestHeader("Content-Type", "application/json;charset=UTF-8");

        let data = {
            categoria: categoria,
            subcategoria: selectedSubcategory,
            talla: selectedSize,
            color: selectedColor
        };

        request.onreadystatechange = function() {
            if (request.readyState === 4 && request.status === 200) {
                try {
                    const response = JSON.parse(request.responseText);
                    if (response.status) {
                        document.querySelector('#productList').innerHTML = response.html;
                    } else {
                        document.querySelector('#productList').innerHTML = '<p>No se encontraron productos.</p>';
                    }
                } catch (error) {
                    console.error('Error al analizar la respuesta JSON:', error);
                    document.querySelector('#productList').innerHTML = '<p>Error al cargar productos.</p>';
                }
            }
        };

        request.send(JSON.stringify(data));
    }

    sizeLabels.forEach(label => label.addEventListener('click', applyFilters));
    colorLabels.forEach(label => label.addEventListener('click', applyFilters));
    subcategoryInputs.forEach(input => input.addEventListener('change', applyFilters));
});
