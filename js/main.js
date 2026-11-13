// Fuentes self-hosted (solo se descargan los subsets/pesos que la página usa)
import '@fontsource-variable/raleway/wght.css';
import '@fontsource-variable/raleway/wght-italic.css';
import '@fontsource/poppins/latin-700.css';
import '@fontsource/poppins/latin-800.css';

// El orden respeta la cascada original: estilos del theme y luego librerías
import '../sass/main.scss';
import '../sass/vendors.scss';

import $ from 'jquery';
import 'owl.carousel';

import initHeader from './modules/header';
import initBanner from './modules/banner';
import initZones from './modules/zones';
import initFaq from './modules/faq';
import initPortfolio from './modules/portfolio';
import initGallery from './modules/gallery';
import initSingleBanner from './modules/single-banner';

window.ditto = {
    menu: (el) => {
        $(el).toggleClass('open');
    }
};

$(() => {
    initHeader();
    initBanner();
    initZones();
    initFaq();
    initPortfolio();
    initGallery();
    initSingleBanner();
});
