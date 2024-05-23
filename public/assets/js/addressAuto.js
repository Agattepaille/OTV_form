var myobject = [];
const adress = document.querySelector("#street");
const autoCompleteJS = new autoComplete({
  selector: "#street",
  data: {
    src: async () => {
      const term = document.querySelector("#street").value;
      if (term) {
        const response = await fetch(
          `https://api-adresse.data.gouv.fr/search/?q=${term}&postcode=59700`
        );
        const json = await response.json();
        myobject = json.features.map(function (el) {
          return {
            label: el.properties.label,
            value: el.properties.label,
            lat: el.geometry.coordinates[1],
            lon: el.geometry.coordinates[0],
            housenumber: el.properties.housenumber,
            name: el.properties.name,
            postcode: el.properties.postcode,
            city: el.properties.city,
            context: el.properties.context,
            type: el.properties.type,
            street: el.properties.street,
            boundingbox: null,
          };
        });
        return myobject.map((el) => el.value);
      } else {
        return [];
      }
    },
    cache: false,
  },
  resultsList: {
    element: (list, data) => {
      if (!data.results.length) {
        const message = document.createElement("div");
        message.setAttribute("class", "no_result");
        message.innerHTML = `<span>Pas de résultat pour "${data.query}"</span>`;
        list.prepend(message);
      }
    },
    noResults: true,
  },
  resultItem: {
    highlight: false,
    element: (item, data) => {
      item.innerHTML = data.match;
      item.classList.add("list-group-item"); // Add Bootstrap class for better styling
    },
  },
  events: {
    input: {
      selection: (event) => {
        const selection = event.detail.selection.value;
        autoCompleteJS.input.value = selection;
        var result = myobject.find(
          (el) => el.value === event.detail.selection.value
        );
        if (result) {
          autoCompleteJS.input.value = result.name;
        } else {
          autoCompleteJS.input.value = "";
        }
      },
    },
  },
});

// Handle keydown events to allow keyboard navigation
adress.addEventListener("keydown", function (event) {
  const list = document.querySelector("#autoComplete_list_1");
  let activeItem = list.querySelector(".activeAddress");
  let nextItem;

  if (event.key === "ArrowDown") {
    event.preventDefault();
    if (activeItem) {
      nextItem = activeItem.nextElementSibling;
      if (nextItem) {
        activeItem.classList.remove("activeAddress");
        nextItem.classList.add("activeAddress");
      }
    } else {
      nextItem = list.querySelector("[role='option']");
      if (nextItem) {
        nextItem.classList.add("activeAddress");
      }
    }
  } else if (event.key === "ArrowUp") {
    event.preventDefault();
    if (activeItem) {
      nextItem = activeItem.previousElementSibling;
      if (nextItem) {
        activeItem.classList.remove("activeAddress");
        nextItem.classList.add("activeAddress");
      }
    }
  } else if (event.key === "Enter") {
    event.preventDefault();
    if (activeItem) {
      autoCompleteJS.input.value = activeItem.innerText;
      autoCompleteJS.input.dispatchEvent(new Event("input", { bubbles: true }));
      list.innerHTML = ""; // Clear the suggestions list after selection
    }
  }
});
