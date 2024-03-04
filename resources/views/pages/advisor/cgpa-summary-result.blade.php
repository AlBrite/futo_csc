<x-template title="Advisors - Results" nav="results">

    <div id="advisor-results" class="flex items-center text-sm font-semibold gap-4 text-body-200">
        <a href="/advisor/results">View Results</a>
        <a href="/advisor/upload-result">Upload Results</a>
        <a href="#" class="active">CGPA Summary Result</a>
    </div>

    <div class="w-full mt-4">
        <form action="" class="flex items-center gap-2 w-full relative md:w-[32rem]">
            <label for="student-search" class="text-body-200 absolute top-3 left-1">
                <span class="material-symbols-rounded">search</span>
            </label>
            <input type="search" name="studentSearch" id="student-search" placeholder="Search..."
                class="border border-[var(--primary)] outline-none h-8  pl-8 text-body-300 w-full">
            <div class="select">
                <select name="searchBy" id="searchBy">
                    <option value="">Search by</option>
                    <option value="name">Name</option>
                    <option value="regNum">Reg. Number</option>
                </select>
            </div>
            <button type="submit"
                class="btn-sm text-white bg-[var(--primary)] hover:bg-[var(--primary-700)] transition rounded h-8">
                Submit
            </button>
        </form>

        <div>
            <button type="button"
                class="btn-sm text-white bg-[var(--primary)] hover:bg-[var(--primary-700)] transition rounded h-8">
                Print
            </button>
        </div>
    </div>

    <div id="advisor-results-container-cgpa-summary" class="pb-4">
        <!-- Display this table if session = "all sessions" -->
        <table id="all-sessions" class="table whitespace-nowrap w-full relative">
            <thead style="text-align: center;">

                <th class="w-5">S/N</th>
                <th>Student Name</th>
                <th>Reg. No.</th>
                <th colspan="3">YEAR 1</th>
                <th></th>
                <th colspan="3">YEAR 2</th>
                <th></th>
                <th colspan="3">YEAR 3</th>
                <th></th>
                <th colspan="3">YEAR 4</th>
                <th></th>
                <th colspan="3">YEAR 5</th>
                <th></th>
                <th colspan="3">Current</th>
            </thead>
            <thead class="text-[.8rem]">
                <th></th>
                <th></th>
                <th></th>

                <th>TGP</th>
                <th>TNU</th>
                <th>CGPA</th>

                <th></th>

                <th>TGP</th>
                <th>TNU</th>
                <th>CGPA</th>

                <th></th>

                <th>TGP</th>
                <th>TNU</th>
                <th>CGPA</th>

                <th></th>

                <th>TGP</th>
                <th>TNU</th>
                <th>CGPA</th>

                <th></th>

                <th>TGP</th>
                <th>TNU</th>
                <th>CGPA</th>

                <th></th>
                <th>TGP</th>
                <th>TNU</th>
                <th>CGPA</th>
            </thead>
            <tbody style="text-align: center;">
                <tr>
                    <td style="text-align: left;">2</td>
                    <td>Amalagu Cosmos</td>
                    <td>20181112222</td>

                    <td>148</td>
                    <td>38</td>
                    <td>4.55</td>
                    <td></td>

                    <td>148</td>
                    <td>38</td>
                    <td>4.55</td>
                    <td></td>

                    <td>148</td>
                    <td>38</td>
                    <td>4.55</td>
                    <td></td>

                    <td>148</td>
                    <td>38</td>
                    <td>4.55</td>
                    <td></td>

                    <td>148</td>
                    <td>38</td>
                    <td>4.55</td>
                    <td></td>

                    <td>148</td>
                    <td>38</td>
                    <td>4.55</td>
                </tr>
                <tr>
                    <td style="text-align: left;">2</td>
                    <td>Amalagu Cosmos</td>
                    <td>20181112222</td>

                    <td>148</td>
                    <td>38</td>
                    <td>4.55</td>
                    <td></td>

                    <td>148</td>
                    <td>38</td>
                    <td>4.55</td>
                    <td></td>

                    <td>148</td>
                    <td>38</td>
                    <td>4.55</td>
                    <td></td>

                    <td>148</td>
                    <td>38</td>
                    <td>4.55</td>
                    <td></td>

                    <td>148</td>
                    <td>38</td>
                    <td>4.55</td>
                    <td></td>

                    <td>148</td>
                    <td>38</td>
                    <td>4.55</td>
                </tr>
                <tr>
                    <td style="text-align: left;">2</td>
                    <td>Amalagu Cosmos</td>
                    <td>20181112222</td>

                    <td>148</td>
                    <td>38</td>
                    <td>4.55</td>
                    <td></td>

                    <td>148</td>
                    <td>38</td>
                    <td>4.55</td>
                    <td></td>

                    <td>148</td>
                    <td>38</td>
                    <td>4.55</td>
                    <td></td>

                    <td>148</td>
                    <td>38</td>
                    <td>4.55</td>
                    <td></td>

                    <td>148</td>
                    <td>38</td>
                    <td>4.55</td>
                    <td></td>

                    <td>148</td>
                    <td>38</td>
                    <td>4.55</td>
                </tr>

            </tbody>
        </table>
        <!--  -->
    </div>

</x-template>
