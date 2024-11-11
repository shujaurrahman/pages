<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Academics</title>
    <link rel="stylesheet" href="./style.css">
    <script>
    document.addEventListener('DOMContentLoaded', function () {
        const buttons = document.querySelectorAll('.button');
        const contentContainer = document.getElementById('content-container');
        const clearButton = document.getElementById('clear-button');

        let data = {};

        // Fetch data from academics.json
        fetch('./academics.json')
            .then(response => response.json())
            .then(jsonData => {
                data = jsonData;
                populateContent('');
            })
            .catch(error => console.error('Error fetching data:', error));

        function groupByYear(items) {
            const groups = {};
            items.forEach(item => {
                const year = item.year;
                if (!groups[year]) {
                    groups[year] = [];
                }
                groups[year].push(item);
            });
            // Sort years in descending order
            const sortedYears = Object.keys(groups).sort((a, b) => b - a);
            return sortedYears.map(year => ({ year, items: groups[year] }));
        }

        function createSection(sectionData, isRecommendation = false) {
            let html = '';
            const groupedData = groupByYear(sectionData);

            groupedData.forEach(yearGroup => {
                html += `
                    <div class="group/posts relative flex border-t border-dashed border-gray-5 max-sm:gap-4">
                        <div class="max-xs:hidden xs:w-[4ch] sm:w-1/5">
                            <h2 class="sticky my-4 text-sm leading-6 text-gray-10" style="top: calc(6vh + 4vw);">
                                ${yearGroup.year}
                            </h2>
                        </div>
                        <div class="grid flex-1 divide-y divide-dashed divide-gray-5">
                `;
                yearGroup.items.forEach(item => {
                    html += `
                        <div class="group/posts relative flex max-xs:flex-col xs:max-sm:gap-4">
                            <div class="xs:w-[4ch] sm:w-1/5">
                                <h3 class="sticky my-4 text-sm leading-6 text-gray-10" style="top: calc(6vh + 4vw);">
                                    ${item.month}
                                </h3>
                            </div>
                            <div class="grid flex-1">
                                <div class="border-b border-dashed border-gray-5 last:border-b-0">
                                    <a href="${isRecommendation ? item.link : item.link || '#'}" rel="noopener noreferrer" target="_blank" class="focusable group -mx-2 flex flex-col px-2 py-4 no-underline">
                                        <div>
                                            <span class="text-gray-12 underline decoration-gray-8 transition-colors group-hfa:decoration-gray-12">
                                                ${isRecommendation ? item.name : item.title}
                                            </span>
                                            <span class="ml-1 text-sm text-gray-8 transition-colors group-hfa:text-gray-12">↗</span>
                                        </div>
                                        ${!isRecommendation && item.icon ? `
                                        <div class="flex items-center gap-2">
                                            <img alt="favicon" loading="lazy" width="16" height="16" decoding="async"
                                                 class="size-4 rounded-sm leading-6" src="${item.icon}" style="color: transparent;">
                                            <span class="shrink-0 text-sm leading-7 text-gray-10">${item.domain}</span>
                                        </div>
                                        ` : ''}
                                        ${isRecommendation ? `
                                        <div class="flex items-center gap-2">
                                            <span class="text-sm leading-7 text-gray-10">${item.position}</span>
                                        </div>
                                        ` : ''}
                                        ${item.description ? `
                                        <span class="mt-3 shrink-0 pl-4 text-sm text-gray-10">
                                            ${item.description}
                                        </span>
                                        ` : ''}
                                        ${item.recommendation ? `
                                        <span class="mt-3 shrink-0 border-l-2 border-gray-6 pl-4 text-sm text-gray-10">
                                            ${item.recommendation}
                                        </span>
                                        ` : ''}
                                        ${item.details ? `
                                        <div class="mt-2 text-sm text-gray-10">
                                            ${item.details}
                                        </div>
                                        ` : ''}
                                    </a>
                                </div>
                            </div>
                        </div>
                    `;
                });
                html += `</div></div>`;
            });
            return html;
        }

        function createAllSections() {
            return `
                ${createSection(data.education)}
                ${createSection(data.certifications)}
                ${createSection(data.workshops)}
                ${createSection(data.skills)}
                ${createSection(data.hackathons)}
                ${createSection(data.recommendations, true)}
            `;
        }

        function populateContent(section) {
            let html = '';
            if (section) {
                switch (section) {
                    case 'education-content':
                        html = createSection(data.education);
                        break;
                    case 'certifications-content':
                        html = createSection(data.certifications);
                        break;
                    case 'workshops-content':
                        html = createSection(data.workshops);
                        break;
                    case 'skills-content':
                        html = createSection(data.skills);
                        break;
                    case 'hackathons-content':
                        html = createSection(data.hackathons);
                        break;
                    case 'recommendations-content':
                        html = createSection(data.recommendations, true);
                        break;
                    default:
                        html = '';
                }
                toggleClearButton(true);
            } else {
                html = createAllSections();
                toggleClearButton(false);
            }
            contentContainer.innerHTML = html;
        }

        function toggleClearButton(show) {
            if (show) {
                clearButton.style.display = 'inline-flex';
            } else {
                clearButton.style.display = 'none';
            }
        }

        buttons.forEach(button => {
            button.addEventListener('click', function (e) {
                e.preventDefault();
                const target = this.getAttribute('data-target');
                populateContent(target);
            });
        });

        clearButton.addEventListener('click', function (e) {
            e.preventDefault();
            populateContent('');
        });
    });
</script>
</head>

<body>
    <main class="container flex flex-1 flex-col py-[calc(4vw+6vh)]">
        <section>
        <header class="not-prose mb-12 flex flex-col items-start leading-normal">
                <h1 class="text-pretty text-xl font-medium text-gray-12">Academics</h1>
                <p class="mt-1 inline-block text-sm text-gray-10">
                    Comprehensive overview of my educational background, certifications, workshops, and skills.
                </p>
            </header>
            <div class="mb-8 flex flex-wrap gap-2">
                <a id="education-button" data-target="education-content"
                class="inline-flex items-center justify-center whitespace-nowrap font-medium transition disabled:pointer-events-none disabled:opacity-50 border border-gray-4 bg-gray-2 hfa:border-gray-5 hfa:bg-gray-3 h-auto rounded-full !border px-2 py-1 text-xs text-gray-10 button"

                    href="javascript:void(0);">Education</a>
                <a id="certifications-button" data-target="certifications-content"
                    class="inline-flex items-center justify-center whitespace-nowrap font-medium transition disabled:pointer-events-none disabled:opacity-50 border border-gray-4 bg-gray-2 hfa:border-gray-5 hfa:bg-gray-3 h-auto rounded-full !border px-2 py-1 text-xs text-gray-10 button"
                    href="javascript:void(0);">Certifications</a>
                <a id="workshops-button" data-target="workshops-content"
                    class="inline-flex items-center justify-center whitespace-nowrap font-medium transition disabled:pointer-events-none disabled:opacity-50 border border-gray-4 bg-gray-2 hfa:border-gray-5 hfa:bg-gray-3 h-auto rounded-full !border px-2 py-1 text-xs text-gray-10 button"
                    href="javascript:void(0);">Workshops</a>
                <a id="skills-button" data-target="skills-content"
                    class="inline-flex items-center justify-center whitespace-nowrap font-medium transition disabled:pointer-events-none disabled:opacity-50 border border-gray-4 bg-gray-2 hfa:border-gray-5 hfa:bg-gray-3 h-auto rounded-full !border px-2 py-1 text-xs text-gray-10 button"
                    href="javascript:void(0);">Skills</a>
                <a id="hackathons-button" data-target="hackathons-content"
                    class="inline-flex items-center justify-center whitespace-nowrap font-medium transition disabled:pointer-events-none disabled:opacity-50 border border-gray-4 bg-gray-2 hfa:border-gray-5 hfa:bg-gray-3 h-auto rounded-full !border px-2 py-1 text-xs text-gray-10 button"
                    href="javascript:void(0);">Hackathons</a>
                <a id="recommendations-button" data-target="recommendations-content"
                    class="inline-flex items-center justify-center whitespace-nowrap font-medium transition disabled:pointer-events-none disabled:opacity-50 border border-gray-4 bg-gray-2 hfa:border-gray-5 hfa:bg-gray-3 h-auto rounded-full !border px-2 py-1 text-xs text-gray-10 button"
                    href="javascript:void(0);">Recommendations</a>
                <a id="clear-button"
                    class="inline-flex items-center justify-center whitespace-nowrap font-medium transition disabled:pointer-events-none disabled:opacity-50 border border-gray-4 bg-gray-2 hfa:border-gray-5 hfa:bg-gray-3 h-auto rounded-full px-2 py-1 text-xs text-gray-10"
                    href="javascript:void(0);">
                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24"
                        fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                        stroke-linejoin="round" class="lucide lucide-x -ml-1 mr-0.5">
                        <path d="M18 6 6 18"></path>
                        <path d="m6 6 12 12"></path>
                    </svg>
                    <span>Clear</span>
                </a>
            </div>

            <!-- Content Container -->
            <div id="content-container">
                <!-- Dynamic content will be populated here -->
            </div>

        </section>
    </main>
</body>

</html>


