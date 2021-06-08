//fno : frame againts number of object
let myChart_fno;
//fd : frame against distance
let myChart_fd;
//fi : frame against Incidents
let myChart_fi;

function generateChartsDemo() {
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
                                'Incidents',
                                'Occurance',
                                'rgba(255, 99, 132, 1)'
                        );

        myChart_fd = makeChart('chart2',
                                'Distance per Frame',
                                data_frameNo,
                                'Frame Number',
                                f0t1,
                                'Distance(m)',
                                'Distance between objects',
                                'rgba(135, 25, 199, 0.47)'
                        );
}


function generateChartsReport() {

        var cC = document.getElementById('chartContainer');
        for(let i = cC.childElementCount; i > 0; i--){
                cC.children[0].remove();
        }

        let title = ['Objects Detected per Frame','Incidents per Frame'];
        let axis_label = ['Objects Detected', 'Incidents Occured'];
        let line_legend = ['Objects', 'Occurance'];
        let line_color = ["rgba(120, 190, 132, 1)","rgba(255, 99, 132, 1)"];
        //let charts = [];

        let dCount = dataFromCSV.length-1 - 3;
        let n;
        for(let i = 1; i < dCount; i++) {
                n = i;
                if((n*(n+1))/2 == dCount) break;
        }
        let obj1 = 0;
        let obj2 = 1;

        for(let i = 1; i < dataFromCSV.length -1; i++){
                let c = document.createElement('canvas');
                c.id = 'chart_' + i;
                c.style.backgroundColor = "white";
                c.style.marginTop = "20px";
                c.style.maxHeight = "325px";

                cC.appendChild(c);

                let label, axis, legend, color;
                if(i<3) {
                        label = title[i-1];
                        axis = axis_label[i-1];
                        legend = line_legend[i-1];
                        color = line_color[i-1];
                }
                else {
                        label = "Distance between Objs";//-"+0+" and Obj-"+i-2;
                        axis = "Distance (m)";
                        legend = obj1.toString() + " - " + obj2.toString();
                        if(obj2 < n) obj2++;
                        else {
                                obj1++;
                                obj2 = obj1 + 1;
                        }

                        let r = Math.floor(Math.random() * 256);
                        let g = Math.floor(Math.random() * 256);
                        let b = Math.floor(Math.random() * 256);
                        //color = 'rbga('+r+','+g+','+b+',1)';
                        color = 'rbga('+r.toString()+','+g.toString()+','+b.toString()+',1)';
                }

                makeChart(c.id,
                        label,
                        dataFromCSV[0],
                        'Frame Number',
                        dataFromCSV[i],
                        axis,
                        legend,
                        color
                );
        }
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
        if(myChart_fno) myChart_fno.update();
        if(myChart_fi) myChart_fi.update();
        if(myChart_fd) myChart_fd.update();
}
