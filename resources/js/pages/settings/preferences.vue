<template>
  <card :title="$t('your_preferences')">
    <form @submit.prevent="update" @keydown="form.onKeydown($event)">
      <alert-success :form="form" :message="$t('info_updated')" />

      <!-- Range -->
      <div class="form-group row">
        <label class="col-md-3 col-form-label text-md-right">
          {{
          $t('range')
          }}
        </label>
        <div class="col-md-7 flex-center">
          <input
            v-model="form.range"
            :class="{ 'is-invalid': form.errors.has('range') }"
            class="custom-range slider"
            type="range"
            name="range"
            min="1"
            max="40000"
            @change="updateRadius()"
          />
          &nbsp;
          <span>{{ form.range }}</span>
          <has-error :form="form" field="range" />
        </div>
      </div>

      <!-- Location -->
      <div class="form-group row">
        <label class="col-md-3 col-form-label text-md-right">
          {{
          $t('location')
          }}
        </label>
        <autocomplete :suggestions="suggestions" v-model="selection"></autocomplete>
      </div>

      <!-- Categories -->
      <div class="form-group row">
        <label class="col-md-3 col-form-label text-md-right">
          {{
          $t('categories')
          }}
        </label>
        <div class="col-md-7">
          <div class="container">
            <ul class="ks-cboxtags">
              <li>
                <input
                  @change="update($event)"
                  type="checkbox"
                  id="checkboxOne"
                  value="coffee"
                  v-model="checkedCategories"
                />
                <label for="checkboxOne">Coffee & Tea</label>
              </li>
              <li>
                <input
                  @change="update($event)"
                  type="checkbox"
                  id="checkboxTwo"
                  value="desserts"
                  v-model="checkedCategories"
                />
                <label for="checkboxTwo">Desserts</label>
              </li>
              <li>
                <input
                  @change="update($event)"
                  type="checkbox"
                  id="checkboxThree"
                  value="meats"
                  v-model="checkedCategories"
                />
                <label for="checkboxThree">Meat</label>
              </li>
              <li>
                <input
                  @change="update($event)"
                  type="checkbox"
                  id="checkboxFour"
                  value="seafoodmarkets"
                  v-model="checkedCategories"
                />
                <label for="checkboxFour">Seafood</label>
              </li>
              <li>
                <input
                  @change="update($event)"
                  type="checkbox"
                  id="checkboxFive"
                  value="bars"
                  v-model="checkedCategories"
                />
                <label for="checkboxFive">Bars</label>
              </li>
              <li>
                <input
                  @change="update($event)"
                  type="checkbox"
                  id="checkboxSix"
                  value="restaurants"
                  v-model="checkedCategories"
                />
                <label for="checkboxSix">Restaurants (all)</label>
              </li>
              <li>
                <input
                  @change="update($event)"
                  type="checkbox"
                  id="checkboxEight"
                  value="french"
                  v-model="checkedCategories"
                />
                <label for="checkboxEight">French</label>
              </li>
              <li class="ks-selected">
                <input
                  @change="update($event)"
                  type="checkbox"
                  id="checkboxEleven"
                  value="chinese"
                  v-model="checkedCategories"
                />
                <label for="checkboxEleven">Chinese</label>
              </li>
              <li>
                <input
                  @change="update($event)"
                  type="checkbox"
                  id="checkboxSeven"
                  value="asianfusion"
                  v-model="checkedCategories"
                />
                <label for="checkboxSeven">Asian Fusion</label>
              </li>
              <li>
                <input
                  @change="update($event)"
                  type="checkbox"
                  id="checkboxNine"
                  value="indpak"
                  v-model="checkedCategories"
                />
                <label for="checkboxNine">Indian</label>
              </li>
              <li>
                <input
                  @change="update($event)"
                  type="checkbox"
                  id="checkboxTen"
                  value="italian"
                  v-model="checkedCategories"
                />
                <label for="checkboxTen">Italian</label>
              </li>
              <li>
                <input
                  @change="update($event)"
                  type="checkbox"
                  id="checkboxFourteen"
                  value="thai"
                  v-model="checkedCategories"
                />
                <label for="checkboxFourteen">Thai</label>
              </li>
              <li>
                <input
                  @change="update($event)"
                  type="checkbox"
                  id="checkboxTwelve"
                  value="japanese"
                  v-model="checkedCategories"
                />
                <label for="checkboxTwelve">Japanese</label>
              </li>
              <li>
                <input
                  @change="update($event)"
                  type="checkbox"
                  id="checkboxThirteen"
                  value="mexican"
                  v-model="checkedCategories"
                />
                <label for="checkboxThirteen">Mexican</label>
              </li>
              <li>
                <input
                  @change="update($event)"
                  type="checkbox"
                  id="checkboxSeventeen"
                  value="turkish"
                  v-model="checkedCategories"
                />
                <label for="checkboxSeventeen">Turkish</label>
              </li>
              <li>
                <input
                  @change="update($event)"
                  type="checkbox"
                  id="checkboxFifteen"
                  value="mideastern"
                  v-model="checkedCategories"
                />
                <label for="checkboxFifteen">Middle Eastern</label>
              </li>
            </ul>
            <span>{{ checkedCategories }}</span>
          </div>
        </div>
      </div>
    </form>
  </card>
</template>

<script>
import Autocomplete from '../../components/Autocomplete'
import Form from 'vform'
import { mapGetters } from 'vuex'
import axios from 'axios'
import { debounce } from 'lodash'

export default {
  scrollToTop: false,
  components: {
    Autocomplete
  },

  metaInfo() {
    return { title: this.$t('settings') }
  },

  data: () => ({
    message: 'Toggle',
    updating: false,
    location: null,
    gettingLocation: false,
    errorStr: null,
    formData: {},
    checkedCategories: [],
    categoryId: '',
    preferences: [],
    selection: '',
    suggestions: [
      'Aberdeen',
      'Abilene',
      'Akron',
      'Albany',
      'Albuquerque',
      'Alexandria',
      'Allentown',
      'Amarillo',
      'Anaheim',
      'Anchorage',
      'Ann Arbor',
      'Antioch',
      'Apple Valley',
      'Appleton',
      'Arlington',
      'Arvada',
      'Asheville',
      'Athens',
      'Atlanta',
      'Atlantic City',
      'Augusta',
      'Aurora',
      'Austin',
      'Bakersfield',
      'Baltimore',
      'Barnstable',
      'Baton Rouge',
      'Beaumont',
      'Bel Air',
      'Bellevue',
      'Berkeley',
      'Bethlehem',
      'Billings',
      'Birmingham',
      'Bloomington',
      'Boise',
      'Boise City',
      'Bonita Springs',
      'Boston',
      'Boulder',
      'Bradenton',
      'Bremerton',
      'Bridgeport',
      'Brighton',
      'Brownsville',
      'Bryan',
      'Buffalo',
      'Burbank',
      'Burlington',
      'Cambridge',
      'Canton',
      'Cape Coral',
      'Carrollton',
      'Cary',
      'Cathedral City',
      'Cedar Rapids',
      'Champaign',
      'Chandler',
      'Charleston',
      'Charlotte',
      'Chattanooga',
      'Chesapeake',
      'Chicago',
      'Chula Vista',
      'Cincinnati',
      'Clarke County',
      'Clarksville',
      'Clearwater',
      'Cleveland',
      'College Station',
      'Colorado Springs',
      'Columbia',
      'Columbus',
      'Concord',
      'Coral Springs',
      'Corona',
      'Corpus Christi',
      'Costa Mesa',
      'Dallas',
      'Daly City',
      'Danbury',
      'Davenport',
      'Davidson County',
      'Dayton',
      'Daytona Beach',
      'Deltona',
      'Denton',
      'Denver',
      'Des Moines',
      'Detroit',
      'Downey',
      'Duluth',
      'Durham',
      'El Monte',
      'El Paso',
      'Elizabeth',
      'Elk Grove',
      'Elkhart',
      'Erie',
      'Escondido',
      'Eugene',
      'Evansville',
      'Fairfield',
      'Fargo',
      'Fayetteville',
      'Fitchburg',
      'Flint',
      'Fontana',
      'Fort Collins',
      'Fort Lauderdale',
      'Fort Smith',
      'Fort Walton Beach',
      'Fort Wayne',
      'Fort Worth',
      'Frederick',
      'Fremont',
      'Fresno',
      'Fullerton',
      'Gainesville',
      'Garden Grove',
      'Garland',
      'Gastonia',
      'Gilbert',
      'Glendale',
      'Grand Prairie',
      'Grand Rapids',
      'Grayslake',
      'Green Bay',
      'GreenBay',
      'Greensboro',
      'Greenville',
      'Gulfport-Biloxi',
      'Hagerstown',
      'Hampton',
      'Harlingen',
      'Harrisburg',
      'Hartford',
      'Havre de Grace',
      'Hayward',
      'Hemet',
      'Henderson',
      'Hesperia',
      'Hialeah',
      'Hickory',
      'High Point',
      'Hollywood',
      'Honolulu',
      'Houma',
      'Houston',
      'Howell',
      'Huntington',
      'Huntington Beach',
      'Huntsville',
      'Independence',
      'Indianapolis',
      'Inglewood',
      'Irvine',
      'Irving',
      'Jackson',
      'Jacksonville',
      'Jefferson',
      'Jersey City',
      'Johnson City',
      'Joliet',
      'Kailua',
      'Kalamazoo',
      'Kaneohe',
      'Kansas City',
      'Kennewick',
      'Kenosha',
      'Killeen',
      'Kissimmee',
      'Knoxville',
      'Lacey',
      'Lafayette',
      'Lake Charles',
      'Lakeland',
      'Lakewood',
      'Lancaster',
      'Lansing',
      'Laredo',
      'Las Cruces',
      'Las Vegas',
      'Layton',
      'Leominster',
      'Lewisville',
      'Lexington',
      'Lincoln',
      'Little Rock',
      'Long Beach',
      'Lorain',
      'Los Angeles',
      'Louisville',
      'Lowell',
      'Lubbock',
      'Macon',
      'Madison',
      'Manchester',
      'Marina',
      'Marysville',
      'McAllen',
      'McHenry',
      'Medford',
      'Melbourne',
      'Memphis',
      'Merced',
      'Mesa',
      'Mesquite',
      'Miami',
      'Milwaukee',
      'Minneapolis',
      'Miramar',
      'Mission Viejo',
      'Mobile',
      'Modesto',
      'Monroe',
      'Monterey',
      'Montgomery',
      'Moreno Valley',
      'Murfreesboro',
      'Murrieta',
      'Muskegon',
      'Myrtle Beach',
      'Naperville',
      'Naples',
      'Nashua',
      'Nashville',
      'New Bedford',
      'New Haven',
      'New London',
      'New Orleans',
      'New York',
      'New York City',
      'Newark',
      'Newburgh',
      'Newport News',
      'Norfolk',
      'Normal',
      'Norman',
      'North Charleston',
      'North Las Vegas',
      'North Port',
      'Norwalk',
      'Norwich',
      'Oakland',
      'Ocala',
      'Oceanside',
      'Odessa',
      'Ogden',
      'Oklahoma City',
      'Olathe',
      'Olympia',
      'Omaha',
      'Ontario',
      'Orange',
      'Orem',
      'Orlando',
      'Overland Park',
      'Oxnard',
      'Palm Bay',
      'Palm Springs',
      'Palmdale',
      'Panama City',
      'Pasadena',
      'Paterson',
      'Pembroke Pines',
      'Pensacola',
      'Peoria',
      'Philadelphia',
      'Phoenix',
      'Pittsburgh',
      'Plano',
      'Pomona',
      'Pompano Beach',
      'Port Arthur',
      'Port Orange',
      'Port Saint Lucie',
      'Port St. Lucie',
      'Portland',
      'Portsmouth',
      'Poughkeepsie',
      'Providence',
      'Provo',
      'Pueblo',
      'Punta Gorda',
      'Racine',
      'Raleigh',
      'Rancho Cucamonga',
      'Reading',
      'Redding',
      'Reno',
      'Richland',
      'Richmond',
      'Richmond County',
      'Riverside',
      'Roanoke',
      'Rochester',
      'Rockford',
      'Roseville',
      'Round Lake Beach',
      'Sacramento',
      'Saginaw',
      'Saint Louis',
      'Saint Paul',
      'Saint Petersburg',
      'Salem',
      'Salinas',
      'Salt Lake City',
      'San Antonio',
      'San Bernardino',
      'San Buenaventura',
      'San Diego',
      'San Francisco',
      'San Jose',
      'Santa Ana',
      'Santa Barbara',
      'Santa Clara',
      'Santa Clarita',
      'Santa Cruz',
      'Santa Maria',
      'Santa Rosa',
      'Sarasota',
      'Savannah',
      'Scottsdale',
      'Scranton',
      'Seaside',
      'Seattle',
      'Sebastian',
      'Shreveport',
      'Simi Valley',
      'Sioux City',
      'Sioux Falls',
      'South Bend',
      'South Lyon',
      'Spartanburg',
      'Spokane',
      'Springdale',
      'Springfield',
      'St. Louis',
      'St. Paul',
      'St. Petersburg',
      'Stamford',
      'Sterling Heights',
      'Stockton',
      'Sunnyvale',
      'Syracuse',
      'Tacoma',
      'Tallahassee',
      'Tampa',
      'Temecula',
      'Tempe',
      'Thornton',
      'Thousand Oaks',
      'Toledo',
      'Topeka',
      'Torrance',
      'Trenton',
      'Tucson',
      'Tulsa',
      'Tuscaloosa',
      'Tyler',
      'Utica',
      'Vallejo',
      'Vancouver',
      'Vero Beach',
      'Victorville',
      'Virginia Beach',
      'Visalia',
      'Waco',
      'Warren',
      'Washington',
      'Waterbury',
      'Waterloo',
      'West Covina',
      'West Valley City',
      'Westminster',
      'Wichita',
      'Wilmington',
      'Winston',
      'Winter Haven',
      'Worcester',
      'Yakima',
      'Yonkers',
      'York',
      'Youngstown'
    ],
    form: new Form({
      range: '',
      location: ''
    })
  }),

  computed: mapGetters({
    user: 'auth/user'
  }),
  async created() {
    // Fill the form with preference data.

    console.log(this.form.keys())

    await axios.get(`/api/settings/preferences/get`).then(response => {
      this.preferences = response.data
      this.fillForm()
    })
  },
  methods: {
    async update(e) {
      console.log(e.target.checked)
      console.log(e.target.value)
      if (e.target.checked) {
        await axios.post(`/api/settings/preferences/update/${e.target.value}`)
      } else {
        await axios.post(`/api/settings/preferences/delete/${e.target.value}`)
      }
      await this.$store.dispatch('preferences/fetchPreferences')
    },
    async fillForm() {
      //console.log('pref', this.preferences)
      this.form.range = this.preferences[0].radius
      //this.location = this.preferences[0].location

      for (let i = 0; i < this.preferences.length; i++) {
        console.log(this.preferences[i].category_name)
        this.checkedCategories[i] = this.preferences[i].category_name
      }
    },
    updateRadius() {
      console.log(this.form.range)
      axios.patch(`/api/settings/preferences/updateRadius/${this.form.range}`)
    },
    getLocation() {
      /*
      zou werken als ik HTTPS hosting had in plaats van HTTP

      if (!('geolocation' in navigator)) {
        this.errorStr = 'Geolocation is not available.'
        return
      }
      this.gettingLocation = true
      // get position
      navigator.geolocation.getCurrentPosition(
        pos => {
          this.gettingLocation = false
          this.location = pos
        },
        err => {
          this.gettingLocation = false
          this.errorStr = err.message
        }
      )
      */
    }
  }
}
</script>
<style lang="scss">
.flex-center {
  display: flex;
  align-items: center;
}
.slider {
  width: 25rem;
}
.custom-btn {
  color: #007bff;
  text-decoration: none;
  background-color: transparent;
  padding-left: 0;
  &:hover {
    text-decoration: underline;
    color: #007bff;
  }
  &:disabled {
    color: grey;
  }
}

ul.ks-cboxtags {
  list-style: none;
  padding: 20px;
}
ul.ks-cboxtags li {
  display: inline;
}
ul.ks-cboxtags li label {
  display: inline-block;
  background-color: rgba(255, 255, 255, 0.9);
  border: 2px solid rgba(139, 139, 139, 0.3);
  color: #adadad;
  border-radius: 25px;
  white-space: nowrap;
  margin: 3px 0px;
  -webkit-touch-callout: none;
  -webkit-user-select: none;
  -moz-user-select: none;
  -ms-user-select: none;
  user-select: none;
  -webkit-tap-highlight-color: transparent;
  transition: all 0.2s;
}

ul.ks-cboxtags li label {
  padding: 8px 12px;
  cursor: pointer;
}

ul.ks-cboxtags li input[type='checkbox']:checked + label {
  border: 2px solid #1bdbf8;
  background-color: #12bbd4;
  color: #fff;
  transition: all 0.2s;
}

ul.ks-cboxtags li input[type='checkbox'] {
  display: absolute;
}
ul.ks-cboxtags li input[type='checkbox'] {
  position: absolute;
  opacity: 0;
}
ul.ks-cboxtags li input[type='checkbox']:focus + label {
  border: 2px solid #e9a1ff;
}
</style>
