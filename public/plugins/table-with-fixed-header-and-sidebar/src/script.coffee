fixedTable = (el) ->
  $body = $(el).find '.fixedTable-body'
  $sidebar = $(el).find '.fixedTable-sidebar table'
  $header = $(el).find '.fixedTable-header table'
  $($body).scroll ->
    $($sidebar).css('margin-top', -$($body).scrollTop())
    $($header).css('margin-left', -$($body).scrollLeft())

demo = new fixedTable $('#demo')