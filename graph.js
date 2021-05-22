//fno : frame-number of object
let myChart_fno;

//make frame against number of object chart
myChart_fno = makeChart('chart0',
                        'Objects Detected per Frame',
                        data_frameNo,
                        'Frame Number',
                        data_numObjDetected,
                        'Number of object',
                        'Objects detected',
                        'rgba(255, 99, 132, 1)'
                );

//graph initializer
function makeChart(container,
                        chart_title,
                        x_data,
                        x_label,
                        y_data,
                        y_label,
                        data_label,
                        line_color
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
              backgroundColor: line_color,
              borderColor: line_color,
              borderCapStyle:'butt',
              borderDash:[],
              borderDashOffset: 0.0,
              borderJoinStyle:'miter',
              pointBorderColor:"rgba(255, 99, 132, 1)",
              pointBackgroundColor:"rgba(255, 99, 132, 1)",
              pointBorderWidth:0.5,
              pointRadius: 0,
              pointHoverRadius:5,
              pointHoverBackgroundColor:"rgba(255, 99, 132, 1)",
              pointHoverBorderColor: "rgbd(220,200,220,1)",
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
                          return value +'m';
                        }
                        },
                        grid: {
                          display: false
                        }
                    }
              }
              },

        });
}

async function update() {
        console.log("update Called");
        //getDataLocal();
        if(stream) {
                if(myChart_fno) myChart_fno.update();

                setTimeout(update,1000);
        }
        else console.log("update(): stream ended");
}

function getDataLocal(){
      if(data_frameNo && data_numObjDetected) {
              console.log("getDataLocal: Data found!")
              //x_fno = data_frameNo;
              //y_fno = data_numObjDetected;
      }
      else {
              console.log("getDataLocal: overallData NOT found!")
      }

      // reset dataset
      // table.forEach(row =>
      // {
      //   const FrameNo = row[0];
      //   x_fno.push(FrameNo);
      //   const obj = row[1];
      //    y_fno.push(obj);
      //   console.log(FrameNo, obj);
      // });

}
