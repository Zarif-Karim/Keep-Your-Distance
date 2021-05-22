//fno : frame againts number of object
let myChart_fno;
//fd : frame against distance
let myChart_fd;
//fi : frame against Incidents
let myChart_fi;

function generateCharts() {
        //make frame against number of object chart
        myChart_fno = makeChart('chart0',
                                'Objects Detected per Frame',
                                data_frameNo,
                                'Frame Number',
                                data_numObjDetected,
                                'Number of object',
                                'Objects detected',
                                'rgba(120, 190, 132, 1)'
                        );

        myChart_fi = makeChart('chart1',
                                'Incidents per Frame',
                                data_frameNo,
                                'Frame Number',
                                data_incidents,
                                'incidents',
                                'occurance',
                                'rgba(255, 99, 132, 1)'
                        );

        myChart_fd = makeChart('chart2',
                                'Distance per Frame',
                                data_frameNo,
                                'Frame Number',
                                f0t1,
                                'distance(m)',
                                'Obj1 - Obj2',
                                'rgba(255, 99, 132, 1)'
                        );
}

//graph initializer
function makeChart(container,
                        chart_title,
                        x_data,
                        x_label,
                        y_data,
                        y_label,
                        data_label,
                        color
                ) {
        //getDataLocal();
        let ctx = document.getElementById(container).getContext('2d');
        return new Chart(ctx, {
          type: 'line',
          data: {
            labels: x_data,
            datasets: [
            {
              data: y_data,
              label: data_label,
              fill:false,
              backgroundColor: color,
              borderColor: color,
              borderCapStyle:'butt',
              borderDash:[],
              borderDashOffset: 0.0,
              borderJoinStyle:'miter',
              pointBorderColor: color,
              pointBackgroundColor: color,
              pointBorderWidth:0.5,
              pointRadius: 0,
              pointHoverRadius:5,
              pointHoverBackgroundColor: color,
              pointHoverBorderColor: "rgbd(50,50,50,1)",
              pointHoverBorderWidth:2,
              pointHitRadius:10,
              lineTension: 0.5,

            }]
          },
          options: {
                responsive: true,
                plugins: {
                  title: {
                    display: true,
                    text: chart_title
                  }
                },
              scales: {
                //x axis detail
                    x: {
                      display: true,
                      title: {
                        display: true,
                        text: x_label
                      }
                    },
                  //y axis detail
                    y:{
                      display: true,
                        title: {
                          display: true,
                          text: y_label
                      },
                      ticks: {
                        min:0,
                        // Include a dollar sign in the ticks
                        callback: function(value, index, values) {
                          return value;
                        }
                        },
                        // grid: {
                        //   display: false
                        // }
                    }
              }
              },

        });
}

async function update() {
        if(stream) {
                if(myChart_fno) myChart_fno.update();
                if(myChart_fi) myChart_fi.update();
                if(myChart_fd) myChart_fd.update();

                setTimeout(update,1000);
        }
        else console.log("update(): stream ended");
}
