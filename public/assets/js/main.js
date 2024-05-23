import { validateSection } from './validation.js';
import setupNavigation from './navigation.js';
import setupEmergencyContacts from './emergencyContacts.js';
import generateRecap from './recap.js';

$(document).ready(function () {
  // Initialisation des différentes fonctionnalités
  validateSection(); 
  setupNavigation();
  setupEmergencyContacts();
  generateRecap();
});
