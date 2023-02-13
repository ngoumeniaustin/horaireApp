let undo = []
let redo = []

const addAction = (e) => {
  undo.push(e);
  redo = []
  console.log(undo);
  
}

const undoAction = () => {
  if (undo.length == 0) return
  let e = undo.pop()
  let previousState = Object.assign({}, e);
  if (e.key == "update") {
    console.log(e)
    switch (e.element) {
      case "group":
        $.getJSON("/api/group/" + e.idGroupe).then((data) => {
         console.log(data)
          previousState = { key: previousState.key, element: previousState.element, ...data[0] }
          console.log(previousState)
          redo.push(previousState)
          executeAction(e)
        
      })
        break
      case "teacher":
        $.getJSON("/api/getTeacher/" + e.idTeacher).then((data) => {
          console.log(data)
           previousState = { key: previousState.key, element: previousState.element, ...data[0] }
           console.log(previousState)
           redo.push(previousState)
           executeAction(e)
         
       })
       break
    }
    
  
  }else {
    
    switch (previousState.key) {
      case "create":
        previousState.key = "delete"
        console.log(e);
        $.post(`/api/${e.element}/${e.key}`, e).then((data) => {
          switch(previousState.element){
            case "teacher":
              previousState.idTeacher=data;
              break;
            case "group":
              previousState.idGroupe=data.idGroup;
              break;
          }
          
          
        });
        break;
      case "delete":
        previousState.key = "create"
        $.post(`/api/${e.element}/${e.key}`, e);
        
        break;
    }
    redo.push(previousState)
    console.log(redo);
    console.log(previousState)
   
  }
}

const redoAction = () => {
  if (redo.length == 0) return
  let e = redo.pop();
  let previousState = Object.assign({}, e);
  if (e.key == "update") {
    switch (e.element) {
      case "group":
        $.getJSON("/api/group/" + e.idGroupe).then((data) => {
         console.log(data)
          previousState = { key: previousState.key, element: previousState.element, ...data[0] }
          console.log(previousState)
          undo.push(previousState)
          executeAction(e)
        
      })
        break
      case "teacher":
        $.getJSON("/api/getTeacher/" + e.idTeacher).then((data) => {
          console.log(data)
           previousState = { key: previousState.key, element: previousState.element, ...data[0] }
           console.log(previousState)
           undo.push(previousState)
           executeAction(e)
         
       })
       break
    }
  }else {
    switch (previousState.key) {
      case "create":
        previousState.key = "delete"
        $.post(`/api/${e.element}/${e.key}`, e).then((data) => {
          switch(previousState.element){
            case "teacher":
              previousState.idTeacher=data;
              break;
            case "group":
              previousState.idGroupe=data.idGroup;
              break;
          }
          
        });
       
        break;
      case "delete":
        console.log(e);
        $.post(`/api/${e.element}/${e.key}`, e);
        previousState.key = "create"
        
       
        break;
    }
    undo.push(previousState)
    
    
  }
}

const executeAction = (e) => {
 
    $.post(`/api/${e.element}/${e.key}`, e);
  
 

}

function addToUndo(key, element, data) {
  addAction({
    key: key,
    element: element,
    ...data
  })
}

$(document).on("click", "button#redo", () => redoAction());
$(document).on("click", "button#undo", () => undoAction());