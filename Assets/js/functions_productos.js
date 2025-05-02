document.write(
  `<script src="${base_url}/Assets/js/plugins/JsBarcode.all.min.js"></script>`
);
let tableProductos;
let rowTable = "";
$(document).on("focusin", function (e) {
  if ($(e.target).closest(".tox-dialog").length) {
    e.stopImmediatePropagation();
  }
});
tableProductos = $("#tableProductos").dataTable({
  aProcessing: true,
  aServerSide: true,
  language: {
    url: `${base_url}/Assets/js/Spanish.json`,
  },
  ajax: {
    url: " " + base_url + "/Productos/getProductos",
    dataSrc: "",
  },
  columns: [
    { data: "idproducto" },
    { data: "codigo" },
    { data: "nombre" },
    { data: "stock" },
    { data: "talla" },
    { data: "precio" },
    { data: "status" },
    { data: "options" },
  ],
  columnDefs: [
    { className: "textcenter", targets: [3] },
    { className: "textright", targets: [4] },
    { className: "textcenter", targets: [5] },
  ],
  dom: "lBfrtip",
  buttons: [
    {
      extend: "copyHtml5",
      text: "<i class='far fa-copy'></i> Copiar",
      titleAttr: "Copiar",
      className: "btn btn-secondary",
      exportOptions: {
        columns: [0, 1, 2, 3, 4, 5],
      },
    },
    {
      extend: "excelHtml5",
      text: "<i class='fas fa-file-excel'></i> Excel",
      titleAttr: "Esportar a Excel",
      className: "btn btn-success",
      exportOptions: {
        columns: [0, 1, 2, 3, 4, 5],
      },
    },
    {
      extend: "pdfHtml5",
      text: "<i class='fas fa-file-pdf'></i> PDF",
      titleAttr: "Esportar a PDF",
      className: "btn btn-danger",
      exportOptions: {
        columns: [0, 1, 2, 3, 4, 5],
      },
    },
    {
      extend: "csvHtml5",
      text: "<i class='fas fa-file-csv'></i> CSV",
      titleAttr: "Esportar a CSV",
      className: "btn btn-info",
      exportOptions: {
        columns: [0, 1, 2, 3, 4, 5],
      },
    },
  ],
  resonsieve: "true",
  bDestroy: true,
  iDisplayLength: 10,
  order: [[0, "desc"]],
});
window.addEventListener(
  "load",
  function () {
    if (document.querySelector("#formProductos")) {
      let formProductos = document.querySelector("#formProductos");
      formProductos.onsubmit = function (e) {
        e.preventDefault();
        let strNombre = document.querySelector("#txtNombre").value;
        let intCodigo = document.querySelector("#txtCodigo").value;
        let strPrecio = document.querySelector("#txtPrecio").value;
        let intStock = document.querySelector("#txtStock").value;
        let intStatus = document.querySelector("#listStatus").value;
        if (
          strNombre == "" ||
          intCodigo == "" ||
          strPrecio == "" ||
          intStock == ""
        ) {
          swal("Atención", "Todos los campos son obligatorios.", "error");
          return false;
        }
        if (intCodigo.length < 5) {
          swal("Atención", "El código debe ser mayor que 5 dígitos.", "error");
          return false;
        }
        divLoading.style.display = "flex";
        tinyMCE.triggerSave();
        let request = window.XMLHttpRequest
          ? new XMLHttpRequest()
          : new ActiveXObject("Microsoft.XMLHTTP");
        let ajaxUrl = base_url + "/Productos/setProducto";
        let formData = new FormData(formProductos);
        request.open("POST", ajaxUrl, true);
        request.send(formData);
        request.onreadystatechange = function () {
          if (request.readyState == 4) {
            divLoading.style.display = "none";

            if (request.status == 200) {
              try {
                let responseText = request.responseText;

                // Verifica si la respuesta es HTML (indicador típico de errores del servidor)
                if (responseText.startsWith("<")) {
                  console.error(
                    "Error: La respuesta del servidor no es JSON, contiene HTML."
                  );
                  console.log(responseText); // Muestra el HTML para depuración
                  swal(
                    "Error",
                    "Hubo un problema con la respuesta del servidor.",
                    "error"
                  );
                  return false;
                }

                // Si no es HTML, intenta parsear el JSON
                let objData = JSON.parse(responseText);

                // Procesar la respuesta JSON como lo hacías antes
                if (objData.status) {
                  swal("", objData.msg, "success");
                  document.querySelector("#idProducto").value =
                    objData.idproducto;
                  document
                    .querySelector("#containerGallery")
                    .classList.remove("notblock");

                  if (rowTable == "") {
                    tableProductos.api().ajax.reload();
                  } else {
                    htmlStatus =
                      intStatus == 1
                        ? '<span class="badge badge-success">Activo</span>'
                        : '<span class="badge badge-danger">Inactivo</span>';
                    rowTable.cells[1].textContent = intCodigo;
                    rowTable.cells[2].textContent = strNombre;
                    rowTable.cells[3].textContent = intStock;
                    rowTable.cells[4].textContent = smony + strPrecio;
                    rowTable.cells[5].innerHTML = htmlStatus;
                    rowTable = "";
                  }
                } else {
                  swal("Error", objData.msg, "error");
                }
              } catch (e) {
                // Manejo de errores al intentar parsear el JSON
                console.error("Error al parsear JSON:", e);
                swal(
                  "Error",
                  "Error inesperado en la respuesta del servidor.",
                  "error"
                );
              }
            } else {
              // Si el estado no es 200, maneja el error
              console.error("Error en la solicitud. Estado: " + request.status);
              swal("Error", "No se pudo completar la solicitud.", "error");
            }
          }
          return false;
        };
      };
    }

    if (document.querySelector(".btnAddImage")) {
      let btnAddImage = document.querySelector(".btnAddImage");
      btnAddImage.onclick = function (e) {
        let key = Date.now();
        let newElement = document.createElement("div");
        newElement.id = "div" + key;
        newElement.innerHTML = `
            <div class="prevImage"></div>
            <input type="file" name="foto" id="img${key}" class="inputUploadfile">
            <label for="img${key}" class="btnUploadfile"><i class="fas fa-upload "></i></label>
            <button class="btnDeleteImage notblock" type="button" onclick="fntDelItem('#div${key}')"><i class="fas fa-trash-alt"></i></button>`;
        document.querySelector("#containerImages").appendChild(newElement);
        document.querySelector("#div" + key + " .btnUploadfile").click();
        fntInputFile();
      };
    }

    fntInputFile();
    fntCategorias();
    fntSubcategorias();
    //fntTallas();
    fntColores();
    //filterProducts();
  },
  false
);

if (document.querySelector("#txtCodigo")) {
  let inputCodigo = document.querySelector("#txtCodigo");
  inputCodigo.onkeyup = function () {
    if (inputCodigo.value.length >= 5) {
      document.querySelector("#divBarCode").classList.remove("notblock");
      fntBarcode();
    } else {
      document.querySelector("#divBarCode").classList.add("notblock");
    }
  };
}

tinymce.init({
  selector: "#txtDescripcion",
  width: "100%",
  height: 400,
  statubar: true,
  plugins: [
    "advlist autolink link image lists charmap print preview hr anchor pagebreak",
    "searchreplace wordcount visualblocks visualchars code fullscreen insertdatetime media nonbreaking",
    "save table contextmenu directionality emoticons template paste textcolor",
  ],
  toolbar:
    "insertfile undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image | print preview media fullpage | forecolor backcolor emoticons",
});

function fntInputFile() {
  let inputUploadfile = document.querySelectorAll(".inputUploadfile");
  inputUploadfile.forEach(function (inputUploadfile) {
    inputUploadfile.addEventListener("change", function () {
      let idProducto = document.querySelector("#idProducto").value;
      let parentId = this.parentNode.getAttribute("id");
      let idFile = this.getAttribute("id");
      let uploadFoto = document.querySelector("#" + idFile).value;
      let fileimg = document.querySelector("#" + idFile).files;
      let prevImg = document.querySelector("#" + parentId + " .prevImage");
      let nav = window.URL || window.webkitURL;
      if (uploadFoto != "") {
        let type = fileimg[0].type;
        let name = fileimg[0].name;
        if (
          type != "image/jpeg" &&
          type != "image/jpg" &&
          type != "image/png"
        ) {
          prevImg.innerHTML = "Archivo no válido";
          uploadFoto.value = "";
          return false;
        } else {
          let objeto_url = nav.createObjectURL(this.files[0]);
          prevImg.innerHTML = `<img class="loading" src="${base_url}/Assets/images/loading.svg" >`;

          let request = window.XMLHttpRequest
            ? new XMLHttpRequest()
            : new ActiveXObject("Microsoft.XMLHTTP");
          let ajaxUrl = base_url + "/Productos/setImage";
          let formData = new FormData();
          formData.append("idproducto", idProducto);
          formData.append("foto", this.files[0]);
          request.open("POST", ajaxUrl, true);
          request.send(formData);
          request.onreadystatechange = function () {
            if (request.readyState != 4) return;
            if (request.status == 200) {
              let objData = JSON.parse(request.responseText);
              if (objData.status) {
                prevImg.innerHTML = `<img src="${objeto_url}">`;
                document
                  .querySelector("#" + parentId + " .btnDeleteImage")
                  .setAttribute("imgname", objData.imgname);
                document
                  .querySelector("#" + parentId + " .btnUploadfile")
                  .classList.add("notblock");
                document
                  .querySelector("#" + parentId + " .btnDeleteImage")
                  .classList.remove("notblock");
              } else {
                swal("Error", objData.msg, "error");
              }
            }
          };
        }
      }
    });
  });
}

function fntDelItem(element) {
  let nameImg = document
    .querySelector(element + " .btnDeleteImage")
    .getAttribute("imgname");
  let idProducto = document.querySelector("#idProducto").value;
  let request = window.XMLHttpRequest
    ? new XMLHttpRequest()
    : new ActiveXObject("Microsoft.XMLHTTP");
  let ajaxUrl = base_url + "/Productos/delFile";

  let formData = new FormData();
  formData.append("idproducto", idProducto);
  formData.append("file", nameImg);
  request.open("POST", ajaxUrl, true);
  request.send(formData);
  request.onreadystatechange = function () {
    if (request.readyState != 4) return;
    if (request.status == 200) {
      let objData = JSON.parse(request.responseText);
      if (objData.status) {
        let itemRemove = document.querySelector(element);
        itemRemove.parentNode.removeChild(itemRemove);
      } else {
        swal("", objData.msg, "error");
      }
    }
  };
}

function fntViewInfo(idProducto) {
  let request = window.XMLHttpRequest
    ? new XMLHttpRequest()
    : new ActiveXObject("Microsoft.XMLHTTP");
  let ajaxUrl = base_url + "/Productos/getProducto/" + idProducto;
  request.open("GET", ajaxUrl, true);
  request.send();
  request.onreadystatechange = function () {
    if (request.readyState == 4 && request.status == 200) {
      try {
        let objData = JSON.parse(request.responseText);
        if (objData.status) {
          // Lógica para mostrar los datos del producto
          let htmlImage = "";
          let objProducto = objData.data;
          let estadoProducto =
            objProducto.status == 1
              ? '<span class="badge badge-success">Activo</span>'
              : '<span class="badge badge-danger">Inactivo</span>';

          document.querySelector("#celCodigo").innerHTML = objProducto.codigo;
          document.querySelector("#celNombre").innerHTML = objProducto.nombre;
          document.querySelector("#celPrecio").innerHTML = objProducto.precio;
          document.querySelector("#celStock").innerHTML = objProducto.stock;
          document.querySelector("#celCategoria").innerHTML =
            objProducto.categoria;
          document.querySelector("#celSubcategoria").innerHTML =
            objProducto.subcategorias;
          document.querySelector("#celTalla").innerHTML = objProducto.talla;
          document.querySelector("#celColor").innerHTML = objProducto.colores;
          document.querySelector("#celStatus").innerHTML = estadoProducto;
          document.querySelector("#celDescripcion").innerHTML =
            objProducto.descripcion;

          if (objProducto.images.length > 0) {
            let objProductos = objProducto.images;
            for (let p = 0; p < objProductos.length; p++) {
              htmlImage += `<img src="${objProductos[p].url_image}" />`;
            }
          }
          document.querySelector("#celFotos").innerHTML = htmlImage;
          $("#modalViewProducto").modal("show");
        } else {
          swal("Error", objData.msg, "error");
        }
      } catch (e) {
        console.error("Error al parsear el JSON:", e);
        console.log(request.responseText); // Mostrar la respuesta que causa el error
      }
    }
  };
}

function fntEditInfo(element, idProducto) {
  rowTable = element.parentNode.parentNode.parentNode;
  document.querySelector("#titleModal").innerHTML = "Actualizar Producto";
  document
    .querySelector(".modal-header")
    .classList.replace("headerRegister", "headerUpdate");
  document
    .querySelector("#btnActionForm")
    .classList.replace("btn-primary", "btn-info");
  document.querySelector("#btnText").innerHTML = "Actualizar";
  let request = window.XMLHttpRequest
    ? new XMLHttpRequest()
    : new ActiveXObject("Microsoft.XMLHTTP");
  let ajaxUrl = base_url + "/Productos/getProducto/" + idProducto;
  request.open("GET", ajaxUrl, true);
  request.send();
  request.onreadystatechange = function () {
    if (request.readyState == 4 && request.status == 200) {
      let objData = JSON.parse(request.responseText);
      if (objData.status) {
        let htmlImage = "";
        let objProducto = objData.data;
        document.querySelector("#idProducto").value = objProducto.idproducto;
        document.querySelector("#txtNombre").value = objProducto.nombre;
        document.querySelector("#txtDescripcion").value =
          objProducto.descripcion;
        document.querySelector("#txtCodigo").value = objProducto.codigo;
        document.querySelector("#txtPrecio").value = objProducto.precio;
        document.querySelector("#txtStock").value = objProducto.stock;
        document.querySelector("#listCategoria").value =
          objProducto.categoriaid;
        document.querySelector("#listStatus").value = objProducto.status;
        tinymce.activeEditor.setContent(objProducto.descripcion);
        $("#listCategoria").selectpicker("render");
        $("#listStatus").selectpicker("render");
        fntBarcode();

        if (objProducto.images.length > 0) {
          let objProductos = objProducto.images;
          for (let p = 0; p < objProductos.length; p++) {
            let key = Date.now() + p;
            htmlImage += `<div id="div${key}">
                            <div class="prevImage">
                            <img src="${objProductos[p].url_image}"></img>
                            </div>
                            <button type="button" class="btnDeleteImage" onclick="fntDelItem('#div${key}')" imgname="${objProductos[p].img}">
                            <i class="fas fa-trash-alt"></i></button></div>`;
          }
        }
        document.querySelector("#containerImages").innerHTML = htmlImage;
        document.querySelector("#divBarCode").classList.remove("notblock");
        document
          .querySelector("#containerGallery")
          .classList.remove("notblock");
        $("#modalFormProductos").modal("show");
      } else {
        swal("Error", objData.msg, "error");
      }
    }
  };
}

function fntDelInfo(idProducto) {
  swal(
    {
      title: "Eliminar Producto",
      text: "¿Realmente quiere eliminar el producto?",
      type: "warning",
      showCancelButton: true,
      confirmButtonText: "Si, eliminar!",
      cancelButtonText: "No, cancelar!",
      closeOnConfirm: false,
      closeOnCancel: true,
    },
    function (isConfirm) {
      if (isConfirm) {
        let request = window.XMLHttpRequest
          ? new XMLHttpRequest()
          : new ActiveXObject("Microsoft.XMLHTTP");
        let ajaxUrl = base_url + "/Productos/delProducto";
        let strData = "idProducto=" + idProducto;
        request.open("POST", ajaxUrl, true);
        request.setRequestHeader(
          "Content-type",
          "application/x-www-form-urlencoded"
        );
        request.send(strData);
        request.onreadystatechange = function () {
          if (request.readyState == 4 && request.status == 200) {
            let objData = JSON.parse(request.responseText);
            if (objData.status) {
              swal("Eliminar!", objData.msg, "success");
              tableProductos.api().ajax.reload();
            } else {
              swal("Atención!", objData.msg, "error");
            }
          }
        };
      }
    }
  );
}

function fntCategorias() {
  if (document.querySelector("#listCategoria")) {
    let ajaxUrl = base_url + "/Categorias/getSelectCategorias";
    let request = window.XMLHttpRequest
      ? new XMLHttpRequest()
      : new ActiveXObject("Microsoft.XMLHTTP");
    request.open("GET", ajaxUrl, true);
    request.send();
    request.onreadystatechange = function () {
      if (request.readyState == 4 && request.status == 200) {
        document.querySelector("#listCategoria").innerHTML =
          request.responseText;
        $("#listCategoria").selectpicker("render");
      }
    };
  }
}

function fntSubcategorias() {
  if (document.querySelector("#listSubcategoria")) {
    let ajaxUrl = base_url + "/Subcategorias/getSelectSubcategorias";
    let request = window.XMLHttpRequest
      ? new XMLHttpRequest()
      : new ActiveXObject("Microsoft.XMLHTTP");
    request.open("GET", ajaxUrl, true);

    request.send();

    request.onreadystatechange = function () {
      if (request.readyState === 4) {
        if (request.status === 200) {
          document.querySelector("#listSubcategoria").innerHTML = "";
          document.querySelector("#listSubcategoria").innerHTML =
            request.responseText;
          $("#listSubcategoria").selectpicker("refresh");
        } else {
        }
      }
    };
  } else {
  }
}
//function filterProducts() {
//    // Obtener los valores seleccionados de los filtros
//    const selectedSubcategory = document.querySelector('input[name="subcategory"]:checked')?.value || '';
//    const selectedSize = document.querySelector('.talla-label input:checked')?.value || '';
//    const selectedColor = document.querySelector('.color-label input:checked')?.value || '';
//
//    // Verificar que al menos un filtro esté seleccionado
//    if (!selectedSubcategory && !selectedSize && !selectedColor) {
//        console.error('No se han seleccionado filtros.');
//        return;
//    }
//
//    // Preparar la URL de la solicitud Ajax
//    let ajaxUrl = base_url + '/Productos/filtrarProductos';  // EndPoint del controlador
//
//    // Crear el objeto de la solicitud
//    let request = new XMLHttpRequest();
//    request.open("POST", ajaxUrl, true);
//    request.setRequestHeader("Content-Type", "application/json;charset=UTF-8");
//
//    // Datos a enviar
//    let data = {
//        subcategoria: selectedSubcategory,
//        talla: selectedSize,
//        color: selectedColor
//    };
//
//    // Verificar que los datos enviados son correctos
//    console.log("Datos enviados:", data);
//
//    // Manejar la respuesta
//    request.onreadystatechange = function() {
//        if (request.readyState === 4) {
//            if (request.status === 200) {
//                // Asegúrate de que el servidor esté enviando HTML en la respuesta
//                console.log("Respuesta del servidor:", request.responseText);
//                document.querySelector('#productList').innerHTML = request.responseText; // Actualiza el contenedor de productos
//            } else {
//                console.error('Error al obtener los productos:', request.status);
//                document.querySelector('#productList').innerHTML = '<p>Error al cargar productos. Intenta nuevamente.</p>'; // Mensaje de error
//            }
//        }
//    };
//
//    // Enviar la solicitud con los datos de filtro en formato JSON
//    request.send(JSON.stringify(data));
//}

//function fntTallas() {
//    if (document.querySelector('#listTalla')) {
//        let ajaxUrl = base_url + '/Tallas/getSelectTallas';
//        let request = (window.XMLHttpRequest) ? new XMLHttpRequest() : new ActiveXObject('Microsoft.XMLHTTP');
//        request.open("GET", ajaxUrl, true);
//        request.send();
//        request.onreadystatechange = function() {
//
//            if (request.readyState === 4) {
//
//                if (request.status === 200) {
//
//                    document.querySelector('#listTalla').innerHTML = "";
//                    document.querySelector('#listTalla').innerHTML = request.responseText;
//
//                    $('#listTalla').selectpicker('refresh');
//                } else {
//                }
//            }
//        };
//    } else {
//    }
//}

function fntColores() {
  if (document.querySelector("#listColor")) {
    let ajaxUrl = base_url + "/Colores/getSelectColores";
    let request = window.XMLHttpRequest
      ? new XMLHttpRequest()
      : new ActiveXObject("Microsoft.XMLHTTP");
    request.open("GET", ajaxUrl, true);
    request.send();

    request.onreadystatechange = function () {
      if (request.readyState === 4) {
        if (request.status === 200) {
          document.querySelector("#listColor").innerHTML = request.responseText;
          $("#listColor").selectpicker("refresh");
          // Agregar el evento para mostrar el color
          $("#listColor").on("change", function () {
            let selectedOption = $(this).find("option:selected");
            let color = selectedOption.data("color");
            let colorText = selectedOption.text(); // Nombre del color
            // Actualiza el cuadrado del color y el nombre
            document.getElementById("colorBox").style.backgroundColor = color;
            document.getElementById("colorName").innerText = colorText;
          });
        } else {
        }
      }
    };
  } else {
  }
}

// Función para cargar las tallas en el select
function fntTallas() {
  //console.log("fntTallas llamada"); // Verifica que la función se llama
  let ajaxUrl = base_url + "/Tallas/getSelectTallas";
  //console.log("URL de solicitud AJAX:", ajaxUrl); // Verifica la URL de la solicitud
  let request = window.XMLHttpRequest
    ? new XMLHttpRequest()
    : new ActiveXObject("Microsoft.XMLHTTP");
  request.open("GET", ajaxUrl, true);
  request.send();
  request.onreadystatechange = function () {
    //console.log("Estado de la solicitud:", request.readyState); // Verifica el estado de la solicitud
    if (request.readyState == 4 && request.status == 200) {
      //console.log("Respuesta de la solicitud:", request.responseText); // Verifica la respuesta recibida
      document.querySelector("#listTallas").innerHTML = request.responseText;
      if (listTallas) {
        // Selecciona opciones por defecto (por ejemplo, las primeras 2)
        listTallas.options[2].selected = true;
        listTallas.options[3].selected = true;
      }
      $("#listTallas").selectpicker("refresh"); // Refresca el selectpicker
    }
  };
}

// Función para manejar el evento submit del formulario
function handleProductFormSubmit() {
  let form = document.getElementById("tallaForm");
  console.log("Formulario encontrado:", form);
  if (!form) {
    return;
  }

  form.addEventListener("submit", function (event) {
    event.preventDefault(); // Prevenir el envío del formulario

    // Obtener los valores seleccionados de listTallas
    let selectedTallas = Array.from(
      document.getElementById("listTallas").selectedOptions
    ).map((option) => option.value);

    // Eliminar campos ocultos existentes para evitar duplicados
    document
      .querySelectorAll('input[name="listTallas[]"]')
      .forEach((input) => input.remove());

    // Crear un campo de entrada oculto para cada talla seleccionada
    selectedTallas.forEach((tallaId) => {
      let hiddenInput = document.createElement("input");
      hiddenInput.type = "hidden";
      hiddenInput.name = "listTallas[]";
      hiddenInput.value = tallaId;
      form.appendChild(hiddenInput);
    });

    // Enviar el formulario
    console.log(
      "Enviando formulario con tallas seleccionadas:",
      selectedTallas
    );
    form.submit(); // Enviamos el formulario
  });
}

// Llamar a las funciones cuando el DOM esté completamente cargado
document.addEventListener("DOMContentLoaded", () => {
  fntTallas(); // Cargar las tallas en el select
  handleProductFormSubmit(); // Configurar el evento de submit del formulario
});

function fntBarcode() {
  let codigo = document.querySelector("#txtCodigo").value;
  JsBarcode("#barcode", codigo);
}

function fntPrintBarcode(area) {
  let elemntArea = document.querySelector(area);
  let vprint = window.open(" ", "popimpr", "height=400,width=600");
  vprint.document.write(elemntArea.innerHTML);
  vprint.document.close();
  vprint.print();
  vprint.close();
}

function openModal() {
  rowTable = "";
  document.querySelector("#idProducto").value = "";
  document
    .querySelector(".modal-header")
    .classList.replace("headerUpdate", "headerRegister");
  document
    .querySelector("#btnActionForm")
    .classList.replace("btn-info", "btn-primary");
  document.querySelector("#btnText").innerHTML = "Guardar";
  document.querySelector("#titleModal").innerHTML = "Nuevo Producto";
  document.querySelector("#formProductos").reset();
  document.querySelector("#divBarCode").classList.add("notblock");
  document.querySelector("#containerGallery").classList.add("notblock");
  document.querySelector("#containerImages").innerHTML = "";
  $("#modalFormProductos").modal("show");
}

document.addEventListener("DOMContentLoaded", function () {
  // Llamar a la función para cargar tallas dinámicamente
  console.log("ID Producto:", idProducto); // Verifica el ID del producto
  fntSelectTallas(idProducto);
});

function fntSelectTallas(idProducto) {
  console.log("fntSelectTallas llamada");
  fetch(`${base_url}/Productos/getTallasproducto/${idProducto}`)
    .then((response) => response.json())
    .then((tallas) => {
      console.log("Tallas recibidas:", tallas); // Debug para verificar datos

      const container = document.getElementById("tallasContainer");
      container.innerHTML = ""; // Limpia el contenedor antes de agregar nuevas opciones

      if (tallas && tallas.length > 0) {
        tallas.forEach((talla) => {
          const radioButton = document.createElement("input");
          radioButton.type = "radio";
          radioButton.id = `talla-${talla.idtalla}`;
          radioButton.name = "talla"; // Cambiado a 'talla' para mantener consistencia con el backend
          radioButton.value = talla.talla;

          const label = document.createElement("label");
          label.setAttribute("for", `talla-${talla.idtalla}`);
          label.className = "size-box";
          label.textContent = talla.talla;

          const div = document.createElement("div");
          div.className = "size-option"; // Clase para el estilo
          div.appendChild(radioButton);
          div.appendChild(label);

          container.appendChild(div);
        });
      } else {
        container.innerHTML =
          "<p>No hay tallas disponibles para este producto.</p>";
      }
    })
    .catch((error) => {
      console.error("Error al cargar las tallas:", error);
    });
}
