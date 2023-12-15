import {data} from './input.mjs'

//this is going to take another 1-2 hours. I will be AFK for a while

const splitNewLine = data.split('\n').filter(element => !!element)
const seeds = splitNewLine.shift().split(': ')[1].split(' ')

let type, element;

const mapping = splitNewLine.reduce((accumulator, line) => {
    if (line.includes(':')) {
        line = line.replace(' map:', '')
        // fertilizer-to-water
        type = line.split('-to-')[0]
        element = line.split('-to-')[1]

        accumulator = {
            ...accumulator,
            [element]: {
                [type]: []
            }
        }

        return accumulator
    } else {
        const [destination, source, range] = line.split(' ')

        accumulator[element][type].push([Number(source), Number(destination), Number(range)])
    }

    return accumulator
}, {})

console.log(mapping);
exit;

let smallestLocation = -1

while (seeds.length > 0) {
    let initialSeed = Number(seeds.shift())
    let count = Number(seeds.shift())

    for (let r = 0; r < count; r++) {
        let seed = initialSeed + r

        console.log(seed)

        element = 'seed'

        let nextKey = seed
        while (true) {
            type = element

            if (mapping[type] === undefined) {
                break;
            }

            element = Object.keys(mapping[type])[0]

            let replaced = false
            mapping[type][element].map(el => {
                if (!replaced) {
                    let source = el[0]
                    let destination = el[1]
                    let range = el[2]

                    if (nextKey >= source && nextKey < source + range) {
                        let distanceFromSource = nextKey - source

                        nextKey = destination + distanceFromSource

                        replaced = true
                    }
                }
            })

        }

        if (smallestLocation === -1 || nextKey < smallestLocation) {
            smallestLocation = nextKey
        }
    }

}

console.log(smallestLocation)
