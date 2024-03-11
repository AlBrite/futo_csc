@php
    $results = new \App\Models\Result();
    $results = $results->groupResultsByLevelSemesterSession();
    $sessions = array_keys($results);
    $values = array_values($results);
    $data = ['rain'=>[], 'harmattan'=>[]];
    foreach($values as $calculations) {
      $data['rain'][] = $calculations['rain']['GPA'];
      $data['harmattan'][] = $calculations['harmattan']['GPA'];
    }
    
@endphp
<x-template>
    
    <div id="chart"></div>

    <script src="{{asset('js/apexchart.js')}}"></script>
    
    <script>
      
      var options = {
        series: [
        {
          name: "Rain",
          data: {!! json_encode($data['rain']) !!},
        },
        {
          name: "Harmattan",
          data: {!! json_encode($data['harmattan']) !!},
        }
      ],
        chart: {
        height: 350,
        type: 'line',
        dropShadow: {
          enabled: true,
          color: '#000',
          top: 18,
          left: 7,
          blur: 10,
          opacity: 0.2
        },
        toolbar: {
          show: false
        }
      },
      colors: ['#77B6EA', '#545454'],
      dataLabels: {
        enabled: true,
      },
      stroke: {
        curve: 'smooth'
      },
      title: {
        text: 'Your performance',
        align: 'left'
      },
      grid: {
        borderColor: '#e7e7e7',
        row: {
          colors: ['#f3f3f3', 'transparent'], // takes an array which will be repeated on columns
          opacity: 0.5
        },
      },
      markers: {
        size: 1
      },
      xaxis: {
        categories: {!!json_encode($sessions)!!},
        title: {
          text: 'Sessions'
        }
      },
      yaxis: {
        title: {
          text: 'Semesters'
        },
        min: 0,
        max: 5
      },
      legend: {
        position: 'top',
        horizontalAlign: 'right',
        floating: true,
        offsetY: -25,
        offsetX: -5
      }
      };

      var chart = new ApexCharts(document.querySelector("#chart"), options);
      chart.render();
    
    
  </script>


</x-template> 