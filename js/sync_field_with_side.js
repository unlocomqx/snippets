const sync_field = 'models'

if (window.dp.stores) {
  init_dp()
} else {
  document.addEventListener('dp.load', function () {
    init_dp()
  })
}

if (window.dsn.stores) {
  init_dsn()
} else {
  document.addEventListener('dsn.load', function () {
    init_dsn()
  })
}

function init_dp() {
  window.dp.stores.calculator.listen(sync_field_to_side)
}

let sync_field_to_side = function (calc) {
  if (!window.dsn_vars.sides) {
    return;
  }
  const selected = calc.input_fields[sync_field].selected_options
  if (!selected[0]) {
    return;
  }
  const option_ids = dp_sort(window.dp.methods.getField(sync_field).options).map(o => +o.id)
  const option_index = option_ids.indexOf(selected[0])
  if (option_index === -1) {
    return;
  }
  const sides = dp_sort(window.dsn_vars.sides)
  const side = sides[option_index]
  if (!side) {
    return;
  }
  window.dsn.stores.ui.set({
    ...window.dsn.stores.ui.state,
    active_side: side.id
  })
};

let sync_side_to_field = function (ui) {
  if (!window.dsn_vars.sides) {
    return;
  }
  const sides = dp_sort(window.dsn_vars.sides)
  const side_index = sides.findIndex(s => +s.id === +ui.active_side)
  if (side_index === -1) {
    return;
  }
  const option = dp_sort(window.dp.methods.getField(sync_field).options)[side_index]
  if (!option) {
    return;
  }
  const selected = window.dp.stores.fields.state[sync_field].selected_options
  if (+selected[0] === +option.id) {
    return;
  }
  window.dp.methods.updateField(sync_field, {selected_options: [+option.id]})
  window.dp.methods.recalculate({changed: sync_field})
};

function init_dsn() {
  sync_field_to_side(window.dp.stores.calculator.state)
  window.dsn.stores.ui.listen(sync_side_to_field)
}

/**
 * Sort items by their position
 *
 * @template {{position: number}} T
 * @param {Object.<string, T>|T[]} items - object map or array of items
 * @returns {T[]} items sorted by ascending position
 */
function dp_sort(items) {
  return Object.values(items).sort((a, b) => a.position - b.position)
}

