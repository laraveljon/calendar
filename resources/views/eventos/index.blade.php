@extends('layouts.app')


@section('scripts')

<link rel="stylesheet" href="{{ asset('fullcalendar/core/main.css') }}"/>
<link rel="stylesheet" href="{{ asset('fullcalendar/daygrid/main.css') }}"/>
<link rel="stylesheet" href="{{ asset('fullcalendar/list/main.css') }}"/>
<link rel="stylesheet" href="{{ asset('fullcalendar/timegrid/main.css') }}"/>

<script src="{{ asset('fullcalendar/core/main.js') }}" defer></script>
<script src="{{ asset('fullcalendar/interaction/main.js') }}" defer></script>
<script src="{{ asset('fullcalendar/daygrid/main.js') }}" defer></script>
<script src="{{ asset('fullcalendar/list/main.js') }}" defer></script>
<script src="{{ asset('fullcalendar/timegrid/main.js') }}" defer></script>

<script>

    document.addEventListener('DOMContentLoaded', function() {
      var calendarEl = document.getElementById('calendar');

      var calendar = new FullCalendar.Calendar(calendarEl, {

        // defaultDate:new Date(2020,7,1),
        plugins: [ 'dayGrid','interaction','timeGrid','list' ],
        // defaultView:'timeGridDay'
        //defaultView:'timeGridWeek'

        header:{
            left:'prev,next today MiBoton',
            center:'title',
            right:'dayGridMonth,timeGridWeek,timeGridDay'

        },

        customButtons:{

            MiBoton:{
                text:"Button",
                click: function(){

                    $('#exampleModal').modal('toggle')

                }
            }
        },
        dateClick:function(info){

            limpiarFormulario();
            
            $('#txtFecha').val(info.dateStr);
            $('#exampleModal').modal();

            // console.log(info);
            // calendar.addEvent({
            //     title:"",
            //     date:info.dateStr
            // });
        },
        eventClick: function(info){
            console.log(info);
            console.log(info.event.title);
            console.log(info.event.start);
            console.log(info.event.textColor);
            console.log(info.event.backgroundColor);

            console.log(info.event.extendedProps.descripcion);

            $('#txtID').val(info.event.id);
            $('#txtTitulo').val(info.event.title);

            mes = (info.event.start.getMonth()+1);
            dia = (info.event.start.getDate());
            anio = (info.event.start.getFullYear());

            mes =(mes <10)?"0"+mes:mes;
            dia =(dia <10)?"0"+dia:dia;

            hora =  (info.event.start.getHours()+":"+ info.event.start.getMinutes());

            $("#txtFecha").val(anio+"-"+mes+"-"+dia);
            $("#txtHora").val(hora);
            $("#txtColor").val(info.event.backgroundColor);

            $('#txtDescripcion').val(info.event.extendedProps.descripcion);

            $('#exampleModal').modal();

        },

        events:"{{ url('/eventos/show') }}"
      });
      calendar.setOption('locale','Es');

      calendar.render();
     // bloque para ingresar
      $('#btnAgregar').click(function(){
        objEvento = recolectarDatosGUI("POST");
        EnviarInformacion('',objEvento);
      });

      // bloque para modificar
      $('#btnModificar').click(function(){
        objEvento = recolectarDatosGUI("PATCH");
        EnviarInformacion('/'+$('#txtID').val(),objEvento);
      });
      // bloque para eliminar
      $('#btnEliminar').click(function(){
        objEvento = recolectarDatosGUI("DELETE");
        EnviarInformacion('/'+$('#txtID').val(),objEvento);
      });

      function recolectarDatosGUI(method){

          nuevoEvento ={
            id:$('#txtID').val(),
            title:$('#txtTitulo').val(),
            descripcion:$('#txtDescripcion').val(),
            color:$("#txtColor").val(),
            textColor:'#FFFFFF',
            start:$("#txtFecha").val() + "  " + $("#txtHora").val(),
            end:$("#txtFecha").val() + "  " + $("#txtHora").val(),
            '_token':$("meta[name='csrf-token']").attr("content"),
            '_method':method  
          }

         return(nuevoEvento)
      }

      function EnviarInformacion(accion,objEvento){
          $.ajax({
              type:"POST",
              url:"{{ url('/eventos')}}"+accion,
              data:objEvento,
              success: function(msg){
                   console.log(msg)

                   $('#exampleModal').modal('toggle');
                   calendar.refetchEvents();
              },
              error: function(){
                alert("hay un error");
              }

          })
      }

        function limpiarFormulario(){

            $('#txtID').val("");
            $('#txtTitulo').val("");

            
            $("#txtFecha").val("");
            $("#txtHora").val("");
            $("#txtColor").val("");

            $('#txtDescripcion').val("");

        }

    });

</script>

@endsection

@section('content')
<div class="row">
    <div class="col"></div>
    <div class="col-10">
        <div id="calendar"></div>
    </div>
    <div class="col"></div>

</div>

  <!-- Modal -->
  <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">Datos de Agenda</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          ID :
            <input type="text" name="txtID" id="txtID" /><br />
          Fecha :
            <input type="text" name="txtFecha" id="txtFecha" /> <br />

            <div class="form-row">
                <div class="form-group col-md-8">
                    <label>Tarea :</label>
                    <input type="text" class="form-control" name="txtTitulo" id="txtTitulo" />
                </div>
                <div class="form-group col-md-4">
                    <label> Hora :</label>
                    <input type="text" class="form-control" name="txtHora" id="txtHora" /> 
                </div>
                <div class="form-group col-md-12">
                    <label> Descripcion de la tarea :</label>
                    <textarea class="form-control" name="txtDescripcion" id="txtDescripcion" cols="30" rows="10"></textarea> 
                </div>
                <div class="form-group col-md-12">
                    <label> Color : </label>
                    <input class="form-control" type="color" name="txtColor" id="txtColor" /> 
                </div>
                
             
              
           
            </div>

        </div>
        <div class="modal-footer">

            <button id="btnAgregar" class="btb btn-success">Agregar</button>
            <button id="btnModificar" class="btb btn-primary">Modificar</button>
            <button id="btnEliminar" class="btb btn-danger">Borrar</button>
            <button id="btnCancelar" class="btb btn-default" data-dismiss="modal">Cancelar</button>

        </div>
      </div>
    </div>
  </div>

@endsection
