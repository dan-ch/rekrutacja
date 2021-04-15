package uwb.licencjat.service;

import org.springframework.beans.factory.annotation.Autowired;
import org.springframework.data.domain.Sort;
import org.springframework.stereotype.Service;
import org.springframework.validation.BindingResult;
import uwb.licencjat.exception.ClinicCategoryNotFoundException;
import uwb.licencjat.exception.ClinicNotFoundException;
import uwb.licencjat.model.Clinic;
import uwb.licencjat.repository.ClinicCategoryRepository;
import uwb.licencjat.repository.ClinicRepository;

import java.util.List;
import java.util.Optional;

@Service
public class ClinicService {

    private ClinicRepository clinicRepository;
    private ClinicCategoryRepository clinicCategoryRepository;

    @Autowired
    public ClinicService(ClinicRepository clinicRepository, ClinicCategoryRepository clinicCategoryRepository) {
        this.clinicRepository = clinicRepository;
        this.clinicCategoryRepository = clinicCategoryRepository;
    }

    public void saveClinic(Clinic clinic){
        clinicRepository.save(clinic);
    }

    public void addCategoryToClinic(Clinic clinic, String category){
        clinicCategoryRepository.findByNameIgnoreCase(category)
                .ifPresentOrElse((clinic::setClinicCategory),
                        ()-> {throw new ClinicCategoryNotFoundException();});
    }

    public boolean existById(long id) {
        return clinicRepository.existsById(id);
    }

    public Clinic findById(long id){
         Optional<Clinic> clinicOptional = clinicRepository.findById(id);
         if(clinicOptional.isPresent()){
             return clinicOptional.get();
        }else{
             throw new ClinicNotFoundException();
         }
    }

    public void deleteById(long id){
        clinicRepository.deleteById(id);
    }

    public List<Clinic> getAllByValueSorted(String value, String sortBy, String sortDirection){
        Sort sort;
        if(sortDirection.equals("desc"))
            sort = Sort.by(Sort.Direction.DESC, sortBy);
        else
            sort = Sort.by(Sort.Direction.ASC, sortBy);
        return clinicRepository.findAllByNameCityProvinceCategoryContains(value, value, value, value, sort);
    }

    public void checkNameUnique(long id, Clinic clinic, BindingResult result){
        Optional<Clinic> clinicFromDb =
                clinicRepository.findByNameIgnoreCase(clinic.getName());
        if(clinicFromDb.isPresent()){
            if(clinicFromDb.get().getId()!=id)
                result.rejectValue("name","UniqueName", "Placówka o takiej nazwie jest już w bazie");
        }
    }

    public void checkNameUnique(Clinic clinic, BindingResult result){
        if(clinicRepository.existsByNameIgnoreCase(clinic.getName()))
            result.rejectValue("name","UniqueName", "Placówka o takiej nazwie jest już w bazie");
    }

}
