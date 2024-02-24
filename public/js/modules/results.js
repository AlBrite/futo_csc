import { extend } from "../extend.js";

function selectSemesterAndSuggestCourses(event) {
  this.semester=event.target.value;
  if(this.semester && this.session) {
    api_get('/enrolledCourses', {
      semester: this.semester,
      session: this.session
    })
    .then(response => {
      this.courses = response;
      console.log(response);
    })
  }
}

function setClass(event) {
  
  const class_id = event.target.value;
  if (class_id) {
    api('/class', {
      class_id
    })
    .then(res => {
      let classes = [];
      for(let year = res.start_year; year < res.start_year+5; year++) {
        classes.push(`${year}/${year+1}`)
      }
      console.log(classes);
      this.sessions = classes;
    })
    .catch(error=>console.error(error))
    
  }


}

function fetchCourse(event){
  const target = event.target;
  const name = target.name;
  if (name) {
    this[name] = target.value;
    if (this.semester && this.level) {
      api_get('/courses', {
        semester: this.semester,
        level: this.level
      })
      .then(response => {
        this.courses = response;
      })
    }
  }
}

extend({selectSemesterAndSuggestCourses, setClass, fetchCourse});