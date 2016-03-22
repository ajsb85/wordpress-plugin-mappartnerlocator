jQuery(document).ready(function(){



    //search: {ele: '#searchbox', fields: ['runtime']}, // With specific fields
  var FJS = FilterJS(partners, '#partners', {
    template: '#partner-template',
    search: {ele: '#searchbox', fields: ['name', 'product', 'country', 'level']},
    callbacks: filter_callbacks
  });


  var filter_callbacks = {
    beforeAddRecords: function(records){
      // Process new JSON data records.
      // i.e Process data before adding to filter while streaming.
    },
    afterAddRecords: function(records){
      // i.e Update google markers or update sorting.
    },
    beforeRender: function(records){
     //
    },
    beforeRecordRender: function(record){
      //i.e Add/Update record fields
    },
    afterFilter: function(result){
      // i.e Update result counter, update google map markers.
      console.log(result);
    }
  };

    filter_callbacks.afterFilter(partners);
  //FJS.setTemplate('#partner-template', true);

  //fJS.addData(data)
  //FJS.addCriteria({field: 'name', ele: '#name_filter'});
  //FJS.addCriteria({field: 'product', ele: '#product_filter'});
  //FJS.addCriteria({field: 'country', ele: '#country_criteria', all: 'all'});
  //FJS.addCriteria({field: 'level', ele: '#level_criteria', all: 'all'});
  window.FJS = FJS;
});
