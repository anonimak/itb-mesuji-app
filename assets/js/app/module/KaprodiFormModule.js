import myAPI from "../request/api.js";

export default (function() {
    // init scope
    let app = context.app
    // component
    let SelectProvince = app.find('#province').select2({
        placeholder:'Cari nama provinsi'
    });
    let SelectRegenct = app.find('#regency').select2({
        placeholder:'Cari nama kabupaten'
    });

    let data = {
        province : {},
        regency : {},
        selectedProvince : SelectProvince.data('selected'),
        selectedRegency : SelectRegenct.data('selected'),
    }

    // events
    SelectProvince.on("change", filterRegency);

    function init(){
        // call if has id form
        if (app.length){
            setDataSelectProvince()
            setDataSelectRegency()
        }
    }

    function setDataSelectProvince(){
        myAPI.getProvince(function (response) {
            // hide loading here
            data.province = [...response.data]
            data.province.forEach(element => {
                SelectProvince.append(new Option(element.name, element.id));
            });
            SelectProvince.val(data.selectedProvince)
        }, function(XMLHttpRequest, textStatus, errorThrown) {
            // hide loading here
        })
    }

    function setDataSelectRegency(){
        myAPI.getRegency(function (response) {
            // hide loading here
            data.regency = [...response.data]
            filterRegency()
        }, function(XMLHttpRequest, textStatus, errorThrown) {
            // hide loading here
        })
    }

    function filterRegency(){
        let idProvince = SelectProvince.find(':selected').val()
        let filteredregency = data.regency.filter(item => item.province_id == idProvince)
        SelectRegenct.find('option').remove()
        filteredregency.forEach(element => {
            if(element.id == data.selectedProvince){
                SelectRegenct.append(new Option(element.name, element.id, true, true))
            } else {
                SelectRegenct.append(new Option(element.name, element.id))
            }
        });
    }

    return {
        init
    }

})()