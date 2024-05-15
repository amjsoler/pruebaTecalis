<?php

if(isset($_POST["datos"])){
    $datos = $_POST["datos"];

//conformar datos de vuelta
    $content = "";

    foreach($datos as $dato){
        $datoJSON = json_decode($dato);

        $content .= $datoJSON->employee_name . ";";
        $content .= $datoJSON->employee_age . ";";
        $content .= $datoJSON->employee_salary . ";";

        if($datoJSON->profile_image != ""){
            $content .= $datoJSON->profile_image . ";";
        }

        $content .= PHP_EOL;
    }


//descargar archivo
    header('Content-Description: File Transfer');
    header('Content-Type: application/octet-stream');
    header('Content-disposition: attachment; filename=datos.txt');
    header('Content-Length: '. strlen($content));
    header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
    header('Expires: 0');
    header('Pragma: public');
    echo $content;
    exit;
}
?>

<html>
<head>
    <title>PHP Test</title>

    <style>
        #tablero {
            margin: 0 auto;
            max-width: 400px;
            display: grid;
            grid-template-columns: 1fr 1fr 1fr 1fr 1fr 1fr 1fr;
        }

        .selected {
            background-color: red;
        }
    </style>
</head>
<body>
<div id="wrapper">

</div>
    <form action="index.php" id="formDatos" method="POST">
    </form>
        <button id="btnSubmit" onclick="guardarHandler()">Guardar.txt</button>

<div id="datos">

</div>
</body>
<script type="text/javascript">

    let datos = []

    //Primero pintamos el tablero de 49 cuadrados por js
    var tablero = document.createElement("div");
    tablero.id = "tablero";
    document.getElementById("wrapper").appendChild(tablero);

    for (var i = 0; i < 49; i++) {
        var cuadrado = document.createElement("div");
        cuadrado.id = i;
        cuadrado.className = "cuadrado";
        cuadrado.style.width = "50px";
        cuadrado.style.height = "50px";
        cuadrado.style.border = "1px solid black";
        cuadrado.style.display = "inline-block";
        cuadrado.style.textAlign = "center";
        cuadrado.style.lineHeight = "50px";
        cuadrado.style.fontSize = "20px";
        cuadrado.style.cursor = "pointer";
        cuadrado.innerHTML = i;
        cuadrado.addEventListener("click", handler);
        tablero.appendChild(cuadrado);
    }

    //función handler para el evento click de cada cuadrado
    function handler(event) {
        var cuadrado = event.target;
        var id = parseInt(cuadrado.innerHTML);

        cuadrado.classList.add("selected")

        fetch("http://lamarr.srv.tecalis.com/pruebaNivel.php?employee=1", {
            headers: {
                'Content-Type': 'application/json',
            },
            mode: "no-cors"
        })
          .then(response => console.log(response))
          .then(data => {
            console.log(data)
          })
            .catch(error => {
                //TODO OJO con el id 25 en adelante
            })

        //TODO: Placeholder para seguir con el ejercicio
        const response = {"id":"1","employee_name":"Tiger Nixon","employee_salary":"320800","employee_age":"61","profile_image":""}



        //Con la respuesta almacenar los datos en el array
        datos[id] = response
    }

    //Función handler para cuando se pulse el botón de guardar.txt
    function guardarHandler(event)
    {
        //event.preventDefault()

        const formDatos = document.getElementById("formDatos")
        let cont = 0;

        //Recorrer el array de datos y montar el div inferior con los datos
        datos.forEach((dato, index) => {
           /* const datosDiv = document.getElementById("datos");



            var datoText = document.createElement("p");
            datoText.innerText = dato.employee_name

            datosDiv.appendChild(datoText);
*/
            //Creo el input hidden con la info
            const inputDatos = document.createElement("input")
            inputDatos.type = "hidden";
            inputDatos.name = `datos[${cont}]`
            inputDatos.value = JSON.stringify({employee_name: dato["employee_name"],employee_salary: dato["employee_salary"], employee_age: dato["employee_age"], profile_image: dato["profile_image"]})

            formDatos.appendChild(inputDatos)

            cont = cont +1;
        })

        formDatos.submit()
    }

</script>
</html>