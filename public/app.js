$(document).ready(function() {



    function selectMany(ev) {



        if (ev.target.getAttribute("id") === 'select_all') {

            document.querySelectorAll("#select").forEach(e => {

                if (e.checked) {
                    e.checked = false
                    // selected_for_delete = [];
                    selected_for_delete = selected_for_delete.filter(id => id !== e.dataset.id)
                    if (selected_for_delete.length === 0) {
                        window.dispatchEvent(new Event('selected_for_delete_remove'));
                    }

                } else {
                    e.checked = true
                    //  console.log(e.dataset.id)
                    if (selected_for_delete.indexOf(e.dataset.id) === -1) {
                        selected_for_delete.push(e.dataset.id)
                    }
                    // selected_for_delete.push(e.dataset.id)
                    if (selected_for_delete.length > 1) {
                        window.dispatchEvent(new Event('selected_for_delete'));
                    }
                }
            })

        }
    }


    function selectOne(ev) {
        if (ev.target.getAttribute("id") === 'select') {
            let e = ev.target
            if (!e.checked) {
                selected_for_delete = selected_for_delete.filter(id => id !== e.dataset.id)
                if (selected_for_delete.length < 2) {
                    window.dispatchEvent(new Event('selected_for_delete_remove'));
                }

            } else {
                //e.checked = true
                //  console.log(e.dataset.id)
                if (selected_for_delete.indexOf(e.dataset.id) === -1) {
                    selected_for_delete.push(e.dataset.id)
                }
                // selected_for_delete.push(e.dataset.id)
                if (selected_for_delete.length > 1) {
                    window.dispatchEvent(new Event('selected_for_delete'));
                }
            }
        }
    }

    // $('#example').dataTable({
    //     "destroy": true
    // });

    let selected_for_delete = [];


    var table = $('#example').DataTable({
            //responsive: true,
            select: false,
            "columnDefs": [{
                // className: "Name",
                "targets": [0,3],
                //  "visible": false,
                // "searchable": false,
                "orderable": false
            }]

        })
        .columns.adjust()
        .responsive.recalc();

    document.querySelector("table").addEventListener("click", function(ev) {
        selectMany(ev)
        selectOne(ev)
    })


    window.addEventListener('selected_for_delete', function() {
        console.log(selected_for_delete)
        document.querySelector(".action").innerHTML = `<button style="background: black;
        border-radius: 5px;padding: 11px;" class="ml-4 bg-black text-white deletemany" data-id="{{$token->id}}">
                                           Delete All</button>`
    })



    window.addEventListener('selected_for_delete_remove', function() {

        document.querySelector(".action").innerHTML = `Action`
    })

    window.addEventListener('err', function(ev) {
        document.querySelector(".err").innerHTML = ev.message
        document.querySelector(".err").style.color = 'red'
        setTimeout(() => {
            document.querySelector(".err").innerHTML = ``
        }, 3000)
    })

    window.addEventListener('suc', function(ev) {
        document.querySelector(".err").innerHTML = ev.message
        document.querySelector(".err").style.color = 'green'
        setTimeout(() => {
            document.querySelector(".err").innerHTML = ``
        }, 3000)
    })


    $('#example').on('click', 'button', function() {  
       
        let but  =  $(this);
        let arr  =but.attr('class').split(/\s+/)
        if(arr.indexOf('deleteone') !== -1  && selected_for_delete.length > 1){
          return confirm('More that on item is selected')
        }
        console.log(but.data('id'),selected_for_delete)
        if( arr.indexOf('deleteone') !== -1 && but.data('id') !==  parseInt(selected_for_delete[0]) ){
            return confirm('Wrong item targeted')
        }
        //if(but)
         

        let w = window.confirm("Are you sure to delete the " + selected_for_delete.length + " selected items?")
        if (w) {
            let tk = document.querySelector("input[name='_token']").value;
            window.request().getData({
                url: '/delete-token/' + JSON.stringify(selected_for_delete),
                method: 'DELETE',
                //body: true,
                appends: [selected_for_delete],
                keys: ['ids'],
                token: tk



            }).then(d => {
               
                if (d.data.suc) {
                    if(arr.indexOf('deleteone') !== -1){
                        $(this).parent().parent().remove()
                      }else{
                       let chk =  $("input:checked")
                       chk.each((i,el)=>{
                        console.log($(el).parent().parent().parent())
                        $(el).parent().parent().parent().remove()

                       })
                      }
                   
                      
                   let ce =   new Event('suc')
                   ce.message  = d.data.suc
                   
                   window.dispatchEvent(ce)/////send event to window to show that item deleted
                   setTimeout(()=>{location.reload()},3000)
                }

                if (d.data.err) {
                    let ce =   new Event('suc')
                    ce.message  = d.data.err
                    window.dispatchEvent(ce)
                }

            }).catch(e => {
                console.log(e)
            })

            //
        }
    })
});