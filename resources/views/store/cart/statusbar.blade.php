<div class="progress-container">
  <div class="progress" id="progress"></div>
    <div class="circle @if(!empty($section3) && $section3 === 'cart') active @endif">
      <i class="icon fa-solid fa-cart-shopping"></i>
      <div class="caption">cart</div>
    </div>
    <div class="circle @if(!empty($section3) && $section3 === 'login') active @endif">
      <i class="icon fa-solid fa-right-to-bracket"></i>
      <div class="caption">login</div>
    </div>
    <!--<div class="circle @if(!empty($section3) && $section3 === 'address') active @endif">
      <i class="icon fa-solid fa-address-book"></i>
      <div class="caption">address</div>
    </div>-->
    <div class="circle @if(!empty($section3) && $section3 === 'payment') active @endif">
      <i class="icon fa-solid fa-credit-card"></i>
      <div class="caption">payment</div>
    </div>
  </div>
</div>


<style>
:root {
    --background-color: #eeeeee;
    --light-grey: #c4c1c1;
    --accent-color: #9acd57;
}
.progress-container {
  display: flex;
  justify-content: space-between;
  position: relative;
  margin: 35px auto 55px auto;
  max-width: 100%;
  width: 350px;
}
.progress-container::before {
  content: "";
  background-color: var(--light-grey);
  position: absolute;
  top: 50%;
  left: 0;
  transform: translateY(-50%);
  height: 4px;
  width: 100%;
  z-index: -1;
}
.progress {
  background-color: var(--accent-color);
  position: absolute;
  top: 50%;
  left: 0;
  transform: translateY(-50%);
  height: 4px;
  width: 0%;
  z-index: -1;
  transition: 0.4s ease;
}

.circle {
  background: var(--light-grey);
  color: var(--light-grey);
  border-radius: 50%;
  height: 10px;
  width: 10px;
  display: flex;
  align-items: center;
  justify-content: center;
  border: 3px solid var(--light-grey);
  transition: 0.4s ease;
}

.circle.active {
  border-color: var(--accent-color);
  background-color: #fff;
  color: var(--accent-color);
}

.circle .icon {
  position: absolute;
  font-size: 25px;
  bottom: 25px;
}

.circle .caption {
  position: absolute;
  font-size: 14px;
  font-weight: bolder;
  bottom: -30px;
}
</style>

<script>
const progress = document.getElementById("progress");
const prev = document.getElementById("prev");
const next = document.getElementById("next");
const cricles = document.querySelectorAll(".circle");

let currentActive = 1;

// Only attach event listeners if the elements exist
if (next) {
  next.addEventListener("click", () => {
    if (currentActive < cricles.length) {
      currentActive++;
    }
    update();
  });
}

if (prev) {
  prev.addEventListener("click", () => {
    if (currentActive > 1) {
      currentActive--;
    }
    update();
  });
}

function update() {
  cricles.forEach((circle, idx) => {
    if (idx < currentActive) {
      circle.classList.add("active");
    } else {
      circle.classList.remove("active");
    }
  });

  if (progress) {
    progress.style.width =
      ((currentActive - 1) / (cricles.length - 1)) * 100 + "%";
  }

  if (prev && next) {
    if (currentActive === 1) {
      prev.disabled = true;
    } else if (currentActive === cricles.length) {
      next.disabled = true;
    } else {
      prev.disabled = false;
      next.disabled = false;
    }
  }
}
</script>
