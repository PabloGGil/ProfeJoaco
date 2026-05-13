    
    // const UrlBase = "http://localhost/vista/Router.php/";
    import { APP_ENV }        from './Config/ConfigEntorno.js';
    const UrlBase = APP_ENV.API_BASE_URL;
    console.log("URL Base: "+APP_ENV.API_BASE_URL);
    

    export async function PostData(servicio,postData){
        try{
            const controlador=servicio.split("/")[0];
            const accion=servicio.split("/")[1];
            const parametros=`?controlador=${controlador}&accion=${accion}`;
            // url=UrlBase + '?q='+$servicio;
            const url=UrlBase +parametros;
            
            const opciones= {
                        method: 'POST', 
                        headers: {
                            'Content-Type': 'application/json', 
                        },
                        body: JSON.stringify(postData), 
                    }
            
            const respuesta= await fetch(url, opciones)

            if (!respuesta.ok) {
                MostrarMensaje(respuesta.errorCode,"alert-danger");
                throw new Error(`Error HTTP: ${respuesta.status}`);
                console.log(respuesta);
            }
            console.log(respuesta);
            return await respuesta.json();
        
            
        } catch (error) {
            console.log('Error en POST:', error);
            throw error;
        }
        
    }

    export async function getData(servicio) {
    try{
        // // url=UrlBase + '?q='+$servicio;
        // const url=UrlBase +servicio;
        const controlador=servicio.split("/")[0];
        const accion=servicio.split("/")[1];
        const parametros=`?controlador=${controlador}&accion=${accion}`;
        // url=UrlBase + '?q='+$servicio;
        const url=UrlBase +parametros;
        const opciones= {
                    method: 'GET', 
                    headers: {
                        'Content-Type': 'application/json', 
                    },
        }
        const response= await fetch(url,opciones)
         if (!response.ok) {
            MostrarMensaje(response.errorCode,"alert-danger");
            throw new Error(`Error HTTP: ${response.status}`);
            console.log(response);
         }
        console.log(response);
        return await response.json();
    
        } catch (error) {
            console.log('Error en GET:', error);
 
        }
    
    }

 

    
    // export {PostData,getData};