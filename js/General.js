    
    const UrlBase = "http://localhost/vista/Router.php/";
    async function PostData(Servicio,postData){
        try{
            const url=UrlBase+Servicio;
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
            console.error('Error en POST:', error);
            throw error;
        }
        
    }
    async function getData(servicio) {
    try{
        // url=UrlBase + '?q='+$servicio;
        const url=UrlBase +servicio;
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
    export {PostData,getData};