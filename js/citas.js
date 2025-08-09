// espera a que el DOM este listo
$(function(){
  var ROL = document.body.dataset.role || '';

  fetchCitas();

  // boton salri
    $(document).ready(function(){
    $('#btnLogout').on('click', function(){
        window.location.href = 'logout.php';
    });
    });

  // boton nueva cita
  $('#btnNew').on('click', function(){
    var $tb = $('#tbl tbody');
    if ($tb.find('.btnSave').length) return;
    $tb.prepend(renderEditRow({}));
  });

  // editar 
  $('#tbl').on('click', '.btnEdit', function(){
    if (ROL !== 'admin') return;
    var $tr = $(this).closest('tr');
    var row = {
      id: $tr.data('id'),
      nombre_paciente: $tr.find('.c-np').text(),
      fecha: $tr.find('.c-fe').text(),
      hora: $tr.find('.c-ho').text() + ':00',
      estado: $tr.find('.c-es').text()
    };
    $tr.replaceWith(renderEditRow(row));
  });

  // cancelar 
  $('#tbl').on('click', '.btnCancel', function(){
    var $tr = $(this).closest('tr');
    var id = $tr.data('id') || 0;
    if (id === 0) { $tr.remove(); return; } // era nueva
    fetchCitas(); // restaura fila original
  });

  // guardar
  $('#tbl').on('click', '.btnSave', function(){
    var $tr = $(this).closest('tr');
    var id = $tr.data('id') || 0;
    var np = $tr.find('input[name=np]').val().trim();
    var fe = $tr.find('input[name=fe]').val().trim();
    var ho = $tr.find('input[name=ho]').val().trim();
    var es = $tr.find('select[name=es]').val();

    if (!np || !fe || !ho) { alert('datos incompletos'); return; }

    $.post('index.php?controller=citas&action=save', {
      id: id, nombre_paciente: np, fecha: fe, hora: ho, estado: es
    })
    .done(function(r){
      if (r && r.status === 'ok') { fetchCitas(); }
      else { alert((r && r.message) || 'error'); }
    })
    .fail(function(){ alert('error de red'); });
  });

  // eliminar 
  $('#tbl').on('click', '.btnDel', function(){
    if (ROL !== 'admin') return;
    if (!confirm('Eliminar cita?')) return;
    var id = $(this).closest('tr').data('id');
    $.post('index.php?controller=citas&action=delete', { id: id })
    .done(function(r){
      if (r && r.status === 'ok') { fetchCitas(); }
      else { alert((r && r.message) || 'error'); }
    })
    .fail(function(){ alert('error de red'); });
  });

  // funciones

  function fetchCitas(){
    $.get('index.php?controller=citas&action=list', function(r){
      if (!r || r.status !== 'ok') { alert('error al cargar'); return; }
      renderRows(r.data || []);
    }).fail(function(){ alert('error de red'); });
  }

  function renderRows(data){
    var $tb = $('#tbl tbody');
    $tb.empty();
    data.forEach(function(row){ $tb.append(renderReadRow(row)); });
  }

  function renderReadRow(row){
    var acciones = '';
    if (ROL === 'admin') {
      acciones = '<button class="btnEdit">Editar</button> ' +
                 '<button class="btnDel">Eliminar</button>';
    } else {
      acciones = '<span>solo lectura</span>';
    }
    return (
      '<tr data-id="'+ row.id +'">' +
        '<td class="c-np">'+ escapeHtml(row.nombre_paciente) +'</td>' +
        '<td class="c-fe">'+ escapeHtml(row.fecha) +'</td>' +
        '<td class="c-ho">'+ row.hora.substring(0,5) +'</td>' +
        '<td class="c-es">'+ escapeHtml(row.estado) +'</td>' +
        '<td class="c-ac">'+ acciones +'</td>' +
      '</tr>'
    );
  }

  function renderEditRow(row){
    var id = row.id || 0;
    var np = row.nombre_paciente || '';
    var fe = row.fecha || '';
    var ho = (row.hora || '').substring(0,5);
    var es = row.estado || 'pendiente';

    var opts = ['pendiente','confirmada','cancelada'].map(function(x){
      var sel = (x === es) ? ' selected' : '';
      return '<option'+ sel +'>'+ x +'</option>';
    }).join('');

    return (
      '<tr data-id="'+ id +'">' +
        '<td><input name="np" value="'+ escapeAttr(np) +'" required></td>' +
        '<td><input type="date" name="fe" value="'+ escapeAttr(fe) +'" required></td>' +
        '<td><input type="time" name="ho" value="'+ escapeAttr(ho) +'" required></td>' +
        '<td><select name="es">'+ opts +'</select></td>' +
        '<td>' +
          '<button class="btnSave">Guardar</button> ' +
          '<button class="btnCancel">Cancelar</button> ' +
          (id ? '<button class="btnDel">Eliminar</button>' : '') +
        '</td>' +
      '</tr>'
    );
  }

  function escapeHtml(s){
    return String(s).replace(/[&<>"']/g, function(m){
      return { '&':'&amp;', '<':'&lt;', '>':'&gt;', '"':'&quot;', "'":'&#39;' }[m];
    });
  }
  function escapeAttr(s){ return escapeHtml(s); }
});
