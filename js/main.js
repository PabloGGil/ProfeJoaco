import { initAdmin } from './admin.js';
import {usuarioConfig} from './Config/UsuariosConfig.js';
import {ejerciciosConfig} from './Config/EjerciciosConfig.js';
import {initPlanesModule} from './Modulos/ABMPlanes.js';

// document.addEventListener('DOMContentLoaded', () => {
  const path = window.location.pathname;
  console.log("----- PATH: " +path);
  if (path.includes('Planes')) {
    // Para entidades simples, usar setupCrud directamente
    console.log("-----  llamo a la inicializacion de Planes" +path);
    initPlanesModule();
    // setupCrud(planesConfig);
  } 
  if (path.includes('Ejercicio')) {
    // Para entidades simples, usar setupCrud directamente
    console.log("-----  llamo a la inicializacion de Ejercicio: " +path);  
    initAdmin(ejerciciosConfig);
  }
  if (path.includes('Usuario')) {
    // Para entidades simples, usar setupCrud directamente
    console.log("----- llamo a la inicializacion de usuario: " +path);
    initAdmin(usuarioConfig);
  }
  