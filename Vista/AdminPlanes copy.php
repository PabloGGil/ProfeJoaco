<?php  include "header.php" ?>
   
    <main>
       <div class="container_error hidden" id="container-error">    
            <div id=mensaje>
    
            </div>
        </div>
        <div class="container">
            <!-- Formulario para agregar/editar plan -->
             <section id="form-section" class="formulario hidden ">
                <div class="form-container">
                    <h2 id="form-title">Agregar Nuevo Plan</h2>
                    
                    <!-- <form id="formulario">
                        <div class="form-row">
                            <div class="form-group">
                                <label for="plan">Plan</label>
                                <input type="text" id="plan" name="plan" required>
                            </div>
                            <div class="form-group">
                            <label for="descripcion">Descripcion</label>
                               
                                <textarea id="descripcion" name="descripcion" rows="4" cols="50" placeholder="Escribe aquí tu comentario de varias líneas..."></textarea><br>
                            </div>
                            <div class="form-group">
                                <label for="ejercicio">Ejercicio</label>
                                <input type="text" id="ejercicio" name="ejercicio" required>
                            </div>
                        </div>
                        
                       
                        <button type="button" class="btn-submit" id="submit-btn">Enviar</button>
                        <button type="button" class="btn-submit" id="cancel-btn" style="background: #6c757d; margin-top: 10px; ">Cancelar</button>
                    </form> -->
                <!-- </div>
            </section>  -->
<!--             <!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestor de Planes de Entrenamiento</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700&display=swap');
        
        body {
            font-family: 'Inter', sans-serif;
            background-color: #f3f4f6;
        }

        .custom-scrollbar::-webkit-scrollbar {
            width: 6px;
        }
        .custom-scrollbar::-webkit-scrollbar-track {
            background: #f1f1f1;
        }
        .custom-scrollbar::-webkit-scrollbar-thumb {
            background: #cbd5e1;
            border-radius: 10px;
        }
        .custom-scrollbar::-webkit-scrollbar-thumb:hover {
            background: #94a3b8;
        }
    </style>
</head> -->

<div class="p-4 md:p-8">

    <div class="max-w-6xl mx-auto">
        <header class="mb-8">
            <h1 class="text-3xl font-bold text-gray-800">Crear Nuevo Plan</h1>
            <p class="text-gray-600">Selecciona los ejercicios de la lista para agregarlos a tu rutina.</p>
        </header>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            
            <!-- Panel Izquierdo: Catálogo de Ejercicios -->
            <div class="lg:col-span-2 bg-white rounded-2xl shadow-sm border border-gray-200 flex flex-col h-[700px]">
                <div class="p-6 border-b border-gray-100">
                    <h2 class="text-xl font-semibold text-gray-700 mb-4">Ejercicios Disponibles</h2>
                    <div class="relative">
                        <input type="text" id="searchInput" placeholder="Buscar ejercicio (ej: Pecho, Piernas...)" 
                               class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent outline-none transition-all">
                        <svg class="w-5 h-5 text-gray-400 absolute left-3 top-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                    </div>
                </div>

                <div id="exerciseList" class="p-6 overflow-y-auto custom-scrollbar flex-grow grid grid-cols-1 md:grid-cols-2 gap-4">
                    <!-- Los ejercicios se cargan mediante JavaScript -->
                </div>
            </div>

            <!-- Panel Derecho: Tu Selección (Plan) -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-200 flex flex-col h-[700px]">
                <div class="p-6 border-b border-gray-100">
                    <h2 class="text-xl font-semibold text-gray-700">Tu Selección</h2>
                    <p id="counter" class="text-sm text-gray-500">0 ejercicios seleccionados</p>
                </div>

                <div id="selectedList" class="p-6 overflow-y-auto custom-scrollbar flex-grow space-y-3 text-center">
                    <!-- Mensaje vacío -->
                    <div id="emptyMessage" class="py-20">
                        <svg class="w-16 h-16 text-gray-200 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                        </svg>
                        <p class="text-gray-400">No hay ejercicios en el plan</p>
                    </div>
                </div>

                <div class="p-6 border-t border-gray-100 bg-gray-50 rounded-b-2xl">
                    <button id="btnSave" onclick="savePlan()" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-3 rounded-xl transition-colors shadow-lg shadow-blue-100 disabled:opacity-50 disabled:cursor-not-allowed">
                        Guardar Plan
                    </button>
                </div>
            </div>
        </div>
    </div>
    </div>
            </section> 
            <!-- Lista  -->
           
            <section id="list-section" class="lista ">
                <div class="form-container">
                    <h2>Lista de Planes</h2>
                    <button  class="btn-submit" id="add-btn">Agregar Plan</button>
                    <div id="container-data">
                        <!-- Los usuarios se cargarán aquí dinámicamente -->
                    </div>
                </div>
            </section>
        </div>
    </main>
    <script type="module" src="../js/Planes.js"></script> 
<?php include "footer.php" ?>    

 