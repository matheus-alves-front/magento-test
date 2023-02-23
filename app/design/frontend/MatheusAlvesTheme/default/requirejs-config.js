var config = {
  paths: {
    slick: 'Magento_Catalog/js/slick.min'
  },
  shim: {
    slick: {
        deps: ['jquery']
    }
  },
  map: {
    '*': {
         'custom-gallery': 'Magento_Catalog/js/custom-gallery'
    }
  }
};
