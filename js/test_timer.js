window.addEventListener('load',function(){
    var time_format = 'HH:mm:ss';
    var empty_time = '00:00:00';
    var DANGER_TIME = 60;//Time when the timer should go red (in seconds)
    
    function _update_timer_color(moment_obj,element)
    {
        if(!moment_obj || !element)
        { 
            console.log("Expected moment object. Possibly invalid element passed");
            return;
        }

        var seconds = (moment_obj.minutes * 60) + moment_obj.seconds;

        //Update the class of the element based on the time left
        if(seconds <= DANGER_TIME)
        {
            var class_name = (moment_obj.hours == 0 && moment_obj.minutes == 0 && moment_obj.seconds == 0) ? 'red-text' : 'amber-text';
            element.classList.add(class_name);//Note: not compatible with IE9
        }
    }

    function start_timer(element)
    {
        if(!element)
        { return; }

        var time = element.innerHTML || empty_time;

        var moment_time = moment(time,time_format);
        var moment_obj = moment_time.toObject();
        _update_timer_color(moment_obj,element);
        setInterval(function(){
            moment_obj = moment_time.toObject();

            //If the time is up, end the timer interval
            if (time == empty_time || (moment_obj.hours == 0 && moment_obj.minutes == 0 && moment_obj.seconds == 0))
            { 
                //TODO: Show modal that says time out
                
                //TODO: Disable 'next question button'
                element.classList.add('disabled');
                
                return; 
            }

            moment_time.subtract(1,'seconds');
            element.innerHTML = moment_time.format(time_format);

            _update_timer_color(moment_obj,element);
        },1000);
        
    }

    function stop_timer(element)
    {
        element.innerHTML = '00:00:00';
    }

    start_timer(document.getElementById('test-timer'));
});