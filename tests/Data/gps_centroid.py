import numpy as np
import numpy.linalg as lin

E = np.array([[0, 0, 1],
              [0, 1, 0],
              [-1, 0, 0]])

def lat_long2n_E(latitude,longitude):
    res = [np.sin(np.deg2rad(latitude)),
           np.sin(np.deg2rad(longitude)) * np.cos(np.deg2rad(latitude)),
           -np.cos(np.deg2rad(longitude)) * np.cos(np.deg2rad(latitude))]
    return np.dot(E.T,np.array(res))

def n_E2lat_long(n_E):
    n_E = np.dot(E, n_E)
    longitude=np.arctan2(n_E[1],-n_E[2]);
    equatorial_component = np.sqrt(n_E[1]**2 + n_E[2]**2 );
    latitude=np.arctan2(n_E[0],equatorial_component);
    return np.rad2deg(latitude), np.rad2deg(longitude)

def average(coords):
    res = []
    for lat,lon in coords:
        res.append(lat_long2n_E(lat,lon))
    res = np.array(res)
    m = np.mean(res,axis=0)
    m = m / lin.norm(m)
    return n_E2lat_long(m)


#paris = [48.85889,2.32004]
#lyon = [45.75781,4.83201]
#marseille = [43.29617,5.36995]
#
## 45.9784058082879, 4.226770011911983
#print (average([paris, lyon, marseille]))

print(average([
    [43.29617,5.36995],
    [43.29616,5.36987],
    [43.29625,5.36998],
    [43.29621,5.37000],
    [43.29616,5.36994]
]))
