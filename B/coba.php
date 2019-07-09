<table border="1" id="myTable">
  <thead>
    <tr>
      <th onclick="sortTable(1)">th1</th>
      <th onclick="sortTable(1)">th1</th>

      <th onclick="sortTable(2)">th1</th>

      <th onclick="sortTable(3)">th1</th>

    </tr>
  </thead>
  <tr>
    <td>Alex</td>
    <td>B</td>
    <td>C</td>
    <td>D</td>
  </tr>
  <tr>
    <td>alu</td>
    <td>1000</td>
    <td>8</td>
    <td>1</td>
  </tr>
  <tr>
    <td>aalex</td>
    <td>9000</td>
    <td>8</td>
    <td>1</td>
  </tr>
  <tr>
    <td>abcd</td>
    <td>30000</td>
    <td>2</td>
    <td>3</td>
  </tr>
  <tr>
    <td>abce</td>
    <td>200000</td>
    <td>4</td>
    <td>0</td>
  </tr>
  <tr>
    <td>abcdef</td>
    <td>100000</td>
    <td>11</td>
    <td>4</td>
  </tr>
  <tr>
    <td>as</td>
    <td>93</td>
    <td>81</td>
    <td>12</td>
  </tr>
</table>
<script type="text/javascript">
  function sortTable(n) {
    var table, rows, switching, i, x, y, shouldSwitch, dir, switchcount = 0;
    table = document.getElementById("myTable");
    switching = true;
    //Set the sorting direction to ascending:
    dir = "asc";
    /*Make a loop that will continue until
    no switching has been done:*/
    while (switching) {
      //start by saying: no switching is done:
      switching = false;
      rows = table.rows;
      /*Loop through all table rows (except the
      first, which contains table headers):*/
      for (i = 1; i < (rows.length - 1); i++) {
        //start by saying there should be no switching:
        shouldSwitch = false;
        /*Get the two elements you want to compare,
        one from current row and one from the next:*/
        x = rows[i].getElementsByTagName("TD")[n];
        y = rows[i + 1].getElementsByTagName("TD")[n];
        /*check if the two rows should switch place,
        based on the direction, asc or desc:*/
        if (dir == "asc") {
          if (Number(x.innerHTML) > Number(y.innerHTML)) {
            //if so, mark as a switch and break the loop:
            shouldSwitch = true;
            break;
          }
        } else if (dir == "desc") {
          if (Number(x.innerHTML) < Number(y.innerHTML)) {
            //if so, mark as a switch and break the loop:
            shouldSwitch = true;
            break;
          }
        }
      }
      if (shouldSwitch) {
        /*If a switch has been marked, make the switch
        and mark that a switch has been done:*/
        rows[i].parentNode.insertBefore(rows[i + 1], rows[i]);
        switching = true;
        //Each time a switch is done, increase this count by 1:
        switchcount++;
      } else {
        /*If no switching has been done AND the direction is "asc",
        set the direction to "desc" and run the while loop again.*/
        if (switchcount == 0 && dir == "asc") {
          dir = "desc";
          switching = true;
        }
      }
    }
  }
</script>